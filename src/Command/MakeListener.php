<?php

namespace Sedehi\Artist\Console\Command;

use Illuminate\Support\Str;
use Sedehi\Artist\Console\Traits\Interactive;
use Sedehi\Artist\Console\Questions\EventlName;
use Sedehi\Artist\Console\Questions\SectionName;
use Sedehi\Artist\Console\Traits\CommandOptions;
use Illuminate\Foundation\Console\ListenerMakeCommand;

class MakeListener extends ListenerMakeCommand implements SectionName, EventlName
{
    use CommandOptions,Interactive;

    protected function getDefaultNamespace($rootNamespace)
    {
        $namespace = $rootNamespace.'\Http';
        if ($this->option('section') !== null) {
            $namespace .= '\Controllers\\'.Str::studly($this->option('section'));
        }

        return $namespace.'\Listeners';
    }
}
