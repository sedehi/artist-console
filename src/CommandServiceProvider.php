<?php

namespace Sedehi\Artist\Console;

use Sedehi\Artist\Console\Command\SectionModel;
use Sedehi\Artist\Console\Command\SectionFactory;
use Illuminate\Foundation\Providers\ArtisanServiceProvider;

class CommandServiceProvider extends ArtisanServiceProvider
{
    protected function registerModelMakeCommand()
    {
        $this->app->singleton('command.model.make', function ($app) {
            return new SectionModel($app['files']);
        });
    }

    protected function registerFactoryMakeCommand()
    {
        $this->app->singleton('command.factory.make', function ($app) {
            return new SectionFactory($app['files']);
        });
    }
}
