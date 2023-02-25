<?php

use App\Models\Line;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up()
    {
        Schema::create('lines', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->timestamps();

            $table->unique('slug');
        });

        $this->addData();
    }

    public function down()
    {
        Schema::dropIfExists('lines');
    }

    private function addData(): void
    {
        $lines = [
            'Bakerloo line',
            'Central line',
            'Circle line',
            'District line',
            'Elizabeth line',
            'Hammersmith & City line',
            'Jubilee line',
            'Metropolitan line',
            'Northern line',
            'Piccadilly line',
            'Victoria line',
            'Waterloo & City line',
            'London Overground',
            'London Buses',
            'Emirates Air Line',
            'DLR',
            'London Trams',
            'TfL Rail',
            'Commuter Train',
        ];

        foreach ($lines as $line) {
            Line::query()->create([
                'name' => $line,
                'slug' => Str::slug($line),
            ]);
        }
    }
};
