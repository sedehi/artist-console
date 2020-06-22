<?php

namespace Sedehi\Artist\Console;

use Illuminate\Foundation\Providers\ArtisanServiceProvider;
use Sedehi\Artist\Console\Command\MakeChannel;
use Sedehi\Artist\Console\Command\MakeCommand;
use Sedehi\Artist\Console\Command\MakeEvent;
use Sedehi\Artist\Console\Command\MakeFactory;
use Sedehi\Artist\Console\Command\MakeJob;
use Sedehi\Artist\Console\Command\MakeListener;
use Sedehi\Artist\Console\Command\MakeMail;
use Sedehi\Artist\Console\Command\MakeMigration;
use Sedehi\Artist\Console\Command\MakeModel;
use Sedehi\Artist\Console\Command\MakeNotification;

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

    protected function registerListenerMakeCommand()
    {
        $this->app->singleton('command.listener.make', function ($app) {
            return new MakeListener($app['files']);
        });
    }

    protected function registerChannelMakeCommand()
    {
        $this->app->singleton('command.channel.make', function ($app) {
            return new MakeChannel($app['files']);
        });
    }

    protected function registerConsoleMakeCommand()
    {
        $this->app->singleton('command.console.make', function ($app) {
            return new MakeCommand($app['files']);
        });
    }

    protected function registerJobMakeCommand()
    {
        $this->app->singleton('command.job.make', function ($app) {
            return new MakeJob($app['files']);
        });
    }

    protected function registerMailMakeCommand()
    {
        $this->app->singleton('command.mail.make', function ($app) {
            return new MakeMail($app['files']);
        });
    }

    protected function registerNotificationMakeCommand()
    {
        $this->app->singleton('command.notification.make', function ($app) {
            return new MakeNotification($app['files']);
        });
    }
}
