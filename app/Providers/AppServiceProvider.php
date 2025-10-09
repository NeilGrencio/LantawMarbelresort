<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Channels\FcmChannel;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->singleton(Messaging::class, function ($app) {
            $factory = (new Factory)
                ->withServiceAccount(base_path('storage/app/firebase/lantawmarbelresort-e4b76-firebase-adminsdk-fbsvc-42809a635b.json'));

            return $factory->createMessaging();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        Notification::extend('fcm', function ($app) {
            return new FcmChannel();
        });


        View::composer('*', function ($view) {
            $view->with('username', Session::get('username'));
            $view->with('avatar', Session::get('avatar'));
        });
        if (file_exists(base_path('routes/api.php'))) {
            Route::prefix('mobile')
                ->middleware('api')
                ->group(base_path('routes/api.php'));
        }
        Schema::defaultStringLength(191);
    }
}
