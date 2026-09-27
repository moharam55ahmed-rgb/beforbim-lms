<?php

namespace App\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Register any application module services.
     */
    public function register(): void
    {
        // Reserved for module-level service bindings
    }

    /**
     * Bootstrap any application module routes, views, and migrations.
     */
    public function boot(): void
    {
        $modulesPath = app_path('Modules');

        if (! File::isDirectory($modulesPath)) {
            return;
        }

        $modules = File::directories($modulesPath);

        foreach ($modules as $modulePath) {
            $moduleName = basename($modulePath);

            // Skip Shared or non-functional directories
            if ($moduleName === 'Shared') {
                continue;
            }

            // Register Web Routes
            $webRouteFile = $modulePath . '/Routes/web.php';
            if (File::exists($webRouteFile)) {
                Route::middleware('web')
                    ->group($webRouteFile);
            }

            // Register API Routes
            $apiRouteFile = $modulePath . '/Routes/api.php';
            if (File::exists($apiRouteFile)) {
                Route::middleware('api')
                    ->prefix('api')
                    ->group($apiRouteFile);
            }
        }
    }
}
