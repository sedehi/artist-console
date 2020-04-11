<?php

namespace Sedehi\Artist\Console;

use Illuminate\Foundation\Providers\ArtisanServiceProvider;
use Sedehi\Artist\Console\Command\MakeEvent;
use Sedehi\Artist\Console\Command\MakeFactory;
use Sedehi\Artist\Console\Command\MakeMigration;
use Sedehi\Artist\Console\Command\MakeModel;

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

    protected function registerEventMakeCommand()
    {
        $this->app->singleton('command.event.make', function ($app) {
            return new MakeEvent($app['files']);
        });
    }
}
