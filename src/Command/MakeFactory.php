<?php

namespace Sedehi\Artist\Console\Command;

use Illuminate\Database\Console\Factories\FactoryMakeCommand;
use Illuminate\Support\Str;
use Sedehi\Artist\Console\Questions\ModelName;
use Sedehi\Artist\Console\Questions\SectionName;
use Sedehi\Artist\Console\Traits\CommandOptions;
use Sedehi\Artist\Console\Traits\Interactive;

class MakeFactory extends FactoryMakeCommand implements SectionName, ModelName
{
    use CommandOptions,Interactive;

    protected function getPath($name)
    {
        $name = str_replace(['\\', '/'], '', $this->argument('name'));
        if ($this->option('section') !== null) {
            return app_path('Http/Controllers/'.Str::studly($this->option('section'))."/database/factories/{$name}.php");
        }

        return $this->laravel->databasePath()."/factories/{$name}.php";
    }
}
