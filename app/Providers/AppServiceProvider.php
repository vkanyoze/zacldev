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
        if ($this->app->runningInConsole()) {
            $this->app->bind('url', function () {
                return new \Illuminate\Routing\UrlGenerator(
                    $this->app['router']->getRoutes(),
                    $this->app->make('request')
                );
            });
        }

        // Register notification components
        $this->registerNotificationComponents();
    }

    /**
     * Register notification components.
     *
     * @return void
     */
    protected function registerNotificationComponents()
    {
        $this->loadViewsFrom(
            resource_path('views/components/notifications'), 'notifications'
        );
    }
}
