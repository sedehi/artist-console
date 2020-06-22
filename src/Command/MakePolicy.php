<?php

namespace Sedehi\Artist\Console\Command;

use Illuminate\Foundation\Console\PolicyMakeCommand;
use Illuminate\Support\Str;
use Sedehi\Artist\Console\Questions\ModelName;
use Sedehi\Artist\Console\Questions\SectionName;
use Sedehi\Artist\Console\Traits\CommandOptions;
use Sedehi\Artist\Console\Traits\Interactive;

class MakePolicy extends PolicyMakeCommand implements SectionName, ModelName
{
    use CommandOptions,Interactive;

    protected function getDefaultNamespace($rootNamespace)
    {
        $namespace = $rootNamespace.'\Http';
        if ($this->option('section') !== null) {
            $namespace .= '\Controllers\\'.Str::studly($this->option('section'));
        }
        return $namespace.'\Policies';
    }
}
