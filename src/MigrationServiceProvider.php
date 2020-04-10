<?php

namespace Sedehi\Artist\Console;

use Illuminate\Database\MigrationServiceProvider as LaravelMigrationServiceProvider;
use Sedehi\Artist\Console\Command\MakeMigration;

class MigrationServiceProvider extends LaravelMigrationServiceProvider
{
    protected function registerMigrateMakeCommand()
    {
        $this->app->singleton('command.migrate.make', function ($app) {
            $creator = $app['migration.creator'];
            $composer = $app['composer'];

            return new MakeMigration($creator, $composer);
        });
    }
}
