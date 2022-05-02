<?php

namespace Tests;

use App\Models\User;
use Closure;
use Database\Seeders\DatabaseSeeder;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Illuminate\Database\Connection;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\SQLiteBuilder;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Fluent;
use Laravel\Dusk\TestCase as BaseTestCase;
use Tests\Browser\Browser;

abstract class DuskTestCase extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseMigrations;

    protected $seeder = DatabaseSeeder::class;
    protected $seed = true;

    protected User $superAdmin;
    protected User $editor;

    /**
     * @beforeClass
     */
    public static function prepare(): void
    {
        if (!static::runningInSail()) {
            static::startChromeDriver();
        }
    }

    protected function newBrowser($driver)
    {
        return (new Browser($driver))->acceptCookies();
    }

    protected function setUp(): void
    {
        $this->hotfixSqlite();
        parent::setUp();

        $this->superAdmin = User::whereEmail('super-admin@example.com')->first();
        $this->editor = User::whereEmail('editor@example.com')->first();
    }

    protected function user(): User
    {
        return $this->superAdmin;
    }

    protected function driver(): RemoteWebDriver
    {
        $options = (new ChromeOptions())->addArguments(
            collect([
                '--window-size=1920,1080',
            ])->unless($this->hasHeadlessDisabled(), function ($items) {
                return $items->merge([
                    '--disable-gpu',
                    '--headless',
                ]);
            })->all()
        );

        return RemoteWebDriver::create(
            $_ENV['DUSK_DRIVER_URL'] ?? 'http://localhost:9515',
            DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY,
                $options
            )
        );
    }

    protected function hasHeadlessDisabled(): bool
    {
        return isset($_SERVER['DUSK_HEADLESS_DISABLED']) ||
            isset($_ENV['DUSK_HEADLESS_DISABLED']);
    }

    /**
     * Fix for: BadMethodCallException : SQLite doesn't support dropping foreign keys (you would need to re-create the table).
     */
    private function hotfixSqlite()
    {
        Connection::resolverFor('sqlite', function ($connection, $database, $prefix, $config) {
            return new class($connection, $database, $prefix, $config) extends SQLiteConnection {
                public function getSchemaBuilder()
                {
                    if ($this->schemaGrammar === null) {
                        $this->useDefaultSchemaGrammar();
                    }

                    return new class($this) extends SQLiteBuilder {
                        protected function createBlueprint($table, Closure $callback = null)
                        {
                            return new class($table, $callback) extends Blueprint {
                                public function dropForeign($index)
                                {
                                    return new Fluent();
                                }
                            };
                        }
                    };
                }
            };
        });
    }
}
