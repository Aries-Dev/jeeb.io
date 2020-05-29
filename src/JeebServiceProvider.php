<?php

namespace Aries\Jeeb;

use Aries\Jeeb\Utils\Callback;
use Aries\Jeeb\Utils\Methods\Confrim;
use Aries\Jeeb\Utils\Methods\Convert;
use Aries\Jeeb\Utils\Methods\Issue;
use Aries\Jeeb\Utils\Methods\Status;
use Aries\Jeeb\Utils\Pay;
use Aries\Jeeb\Utils\State;
use Aries\Jeeb\Utils\Transaction;
use Aries\Jeeb\Utils\Webhook;
use Illuminate\Support\ServiceProvider;

class JeebServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/jeeb.php', 'jeeb'
        );

        $this->app->bind('Jeeb', function() {
            return new Jeeb();
        });

        $this->app->bind('Convert', function() {
            return new Convert();
        });

        $this->app->bind('Issue', function() {
            return new Issue();
        });

        $this->app->bind('Status', function() {
            return new Status();
        });

        $this->app->bind('Confirm', function() {
            return new Confrim();
        });

        $this->app->bind('Pay', function() {
            return new Pay();
        });

        $this->app->bind('Callback', function() {
            return new Callback();
        });

        $this->app->bind('Webhook', function() {
            return new Webhook();
        });

        $this->app->bind('State', function() {
            return new State();
        });

        $this->app->bind('Transaction', function() {
            return new Transaction();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/jeeb.php'    =>  config_path('jeeb.php')
        ], 'config');

        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        $this->publishes([
            __DIR__.'/migrations'   =>  base_path('database/migrations')
        ], 'migrations');
    }
}