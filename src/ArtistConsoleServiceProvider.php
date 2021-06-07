<?php

namespace Sedehi\Artist\Console;

use Illuminate\Support\ServiceProvider;

class ArtistConsoleServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(CommandServiceProvider::class);
        if (class_exists(\Illuminate\Database\MigrationServiceProvider::class)) {
            $this->app->register(MigrationServiceProvider::class);
        }
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {

        $this->publishes([
            __DIR__.'/../src/Command/stubs/sections/Role' => app_path('Http/Controllers/Role'),
        ], 'section-role-directory');
        $this->publishes([
            __DIR__.'/../src/Command/stubs/sections/User' => app_path('Http/Controllers/User'),
        ], 'section-user-directory');
    }
}
