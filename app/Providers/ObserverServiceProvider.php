<?php

namespace BB\Providers;

use BB\Entities\Activity;
use BB\Entities\User;
use BB\Observer\ActivityObserver;
use BB\Observer\UserAuditObserver;
use BB\Observer\UserObserver;
use Illuminate\Support\ServiceProvider;

class ObserverServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        User::observe(new UserObserver());
        User::observe(new UserAuditObserver());
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
