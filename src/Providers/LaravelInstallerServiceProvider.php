<?php

namespace Liteas98\LaravelInstaller\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Liteas98\LaravelInstaller\Middleware\canInstall;
use Liteas98\LaravelInstaller\Middleware\canUpdate;

class LaravelInstallerServiceProvider extends ServiceProvider
{
    protected bool $defer = false;

    public function register(): void
    {
        $this->publishFiles();
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
    }

    public function boot(Router $router): void
    {
        $router->middlewareGroup('install', [CanInstall::class]);
        $router->middlewareGroup('update', [CanUpdate::class]);
    }

    protected function publishFiles(): void
    {
        $this->publishes([
            __DIR__.'/../Config/installer.php' => base_path('config/installer.php'),
        ], 'laravelinstaller');

        $this->publishes([
            __DIR__.'/../assets' => public_path('installer'),
        ], 'laravelinstaller');

        $this->publishes([
            __DIR__.'/../Views' => base_path('resources/views/vendor/installer'),
        ], 'laravelinstaller');

        $this->publishes([
            __DIR__.'/../Lang' => base_path('resources/lang'),
        ], 'laravelinstaller');
    }
}
