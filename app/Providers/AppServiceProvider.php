<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Route;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        View::composer('*', function ($view) {
            $view->with('username', Session::get('username'));
            $view->with('avatar', Session::get('avatar'));
        });
        if (file_exists(base_path('routes/api.php'))) {
            Route::prefix('mobile')
                ->middleware('api')
                ->group(base_path('routes/api.php'));
        }
    }
}
