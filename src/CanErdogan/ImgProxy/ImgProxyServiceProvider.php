<?php
namespace CanErdogan\ImgProxy;

use CanErdogan\ImgProxy\Contracts\ImageSignatureInterface;
use Illuminate\Support\ServiceProvider;

class ImgProxyServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the service.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../../tests/config/img-proxy.php' => config_path('img-proxy.php'),
        ]);
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->bind(ImageSignatureInterface::class, function ($app) {
            return new ImageSignature($app->make(Image::class));
        });
    }
}
