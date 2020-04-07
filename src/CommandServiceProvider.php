<?php


namespace Sedehi\Artist\Console;


use Illuminate\Foundation\Providers\ArtisanServiceProvider;
use Sedehi\Artist\Console\Command\SectionModel;

class CommandServiceProvider extends ArtisanServiceProvider
{
    protected function registerModelMakeCommand()
    {
        $this->app->singleton('command.model.make', function ($app) {
            return new SectionModel($app['files']);
        });
    }
}
