<?php

namespace Sedehi\Artist\Console\Command;

use Illuminate\Support\Str;
use Sedehi\Artist\Console\Traits\CommandOptions;
use Illuminate\Database\Console\Factories\FactoryMakeCommand;

class MakeFactory extends FactoryMakeCommand
{
    use CommandOptions;

    protected function getPath($name)
    {
        $name = str_replace(['\\', '/'], '', $this->argument('name'));
        if ($this->option('section') !== null) {
            return app_path('Http/Controllers/'.Str::studly($this->option('section'))."/database/factories/{$name}.php");
        }

        return $this->laravel->databasePath()."/factories/{$name}.php";
    }
}
