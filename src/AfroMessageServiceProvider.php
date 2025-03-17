<?php

namespace Afromessage;

use Illuminate\Support\ServiceProvider;

class AfroMessageServiceProvider extends ServiceProvider
{
    public function boot()
    {

        $this->publishes([
            __DIR__.'/../config/afromessage.php' => config_path('afromessage.php'),
        ],
            'afromessage-config'
        );

    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/afromessage.php', 'afromessage');
    }
}
