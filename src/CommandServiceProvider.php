<?php

namespace Sedehi\Artist\Console;

use Sedehi\Artist\Console\Command\MakeMigration;
use Sedehi\Artist\Console\Command\MakeModel;
use Sedehi\Artist\Console\Command\MakeFactory;
use Illuminate\Foundation\Providers\ArtisanServiceProvider;

class CommandServiceProvider extends ArtisanServiceProvider
{
    protected function registerModelMakeCommand()
    {
        $this->app->singleton('command.model.make', function ($app) {
            return new MakeModel($app['files']);
        });
    }

    protected function registerFactoryMakeCommand()
    {
        $this->app->singleton('command.factory.make', function ($app) {
            return new MakeFactory($app['files']);
        });
    }

    protected function registerMigrateMakeCommand()
    {
        $this->app->singleton('command.migrate.make', function ($app) {
            $creator = $app['migration.creator'];
            $composer = $app['composer'];

            return new MakeMigration($creator, $composer);
        });
    }
}
