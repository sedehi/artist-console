<?php

namespace Sedehi\Artist\Console\Command;

use Illuminate\Foundation\Console\ResourceMakeCommand;
use Illuminate\Support\Str;
use Sedehi\Artist\Console\Questions\ApiVersion;
use Sedehi\Artist\Console\Questions\SectionName;
use Sedehi\Artist\Console\Traits\Interactive;
use Symfony\Component\Console\Input\InputOption;

class MakeResource extends ResourceMakeCommand implements SectionName, ApiVersion
{
    use Interactive;

    protected function getOptions()
    {
        $options = parent::getOptions();
        $options = array_merge($options, [
            ['section', null, InputOption::VALUE_OPTIONAL, 'The name of the section'],
            ['in', false, InputOption::VALUE_NONE, 'Interactive mode'],
            ['api-version', 'av', InputOption::VALUE_OPTIONAL, 'Set api version'],
        ]);

        return $options;
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        $namespace = $rootNamespace.'\Http';
        if ($this->option('section') !== null) {
            $namespace .= '\Controllers\\'.Str::studly($this->option('section'));
        }
        if ($this->option('api-version') !== null) {
            return $namespace.'\Resources\\'.Str::studly($this->option('api-version'));
        }

        return $namespace.'\Resources';
    }
}
