<?php

namespace App\Providers;

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
        if (!class_exists('Helper')) {
            class_alias(\App\Helpers\Helpers::class, 'Helper');
        }

        // Add Hatchery Notifications to Navbar
        view()->composer('layouts/sections/navbar/navbar', function ($view) {
            $notificationService = app(\App\Services\NotificationService::class);
            $notifications = $notificationService->getActiveNotifications();
            $view->with('hatcheryNotifications', $notifications);
        });
    }
}
