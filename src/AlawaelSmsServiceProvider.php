<?php

namespace SprigYe\AlawaelSms;

use Illuminate\Support\ServiceProvider;

class AlawaelSmsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/alawael-sms.php' => config_path('alawael-sms.php'),
        ], 'alawael-config');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/alawael-sms.php', 'alawael-sms');

        $this->app->singleton('alawael-sms', function () {
            return new AlawaelSmsClient(config('alawael-sms'));
        });
    }
}
