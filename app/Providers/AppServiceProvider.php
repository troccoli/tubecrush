<?php

namespace App\Providers;

use App\Contracts\VotingService;
use App\Services\VotingCookie;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public $bindings = [
        VotingService::class => VotingCookie::class,
    ];

    public function register(): void
    {
    }

    public function boot(): void
    {
    }
}
