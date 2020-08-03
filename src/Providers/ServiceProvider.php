<?php

namespace ShibuyaKosuke\LaravelDompdfFont\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider as ServiceProviderBase;
use ShibuyaKosuke\LaravelDompdfFont\Console\DompdfFont;

/**
 * Class ServiceProvider
 * @package ShibuyaKosuke\LaravelDompdfFont\Providers
 */
class ServiceProvider extends ServiceProviderBase implements DeferrableProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../configs/pdf_font.php' => config_path('pdf_font.php'),
        ]);

        $this->app->singleton('pdf-font', function () {
            return new DompdfFont();
        });

        $this->commands(['pdf-font']);
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../configs/pdf_font.php', 'pdf_font'
        );
    }

    public function provides()
    {
        return ['pdf-font'];
    }
}