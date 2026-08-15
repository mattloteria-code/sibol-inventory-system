<?php

namespace App\Providers;

use App\Models\Notification;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            $view->with('unreadNotifications', Notification::where('is_read', false)->latest('created_at')->take(10)->get());
            $view->with('unreadNotificationCount', Notification::where('is_read', false)->count());
        });
    }
}
