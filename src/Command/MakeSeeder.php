<?php

namespace Sedehi\Artist\Console\Command;

use Illuminate\Database\Console\Seeds\SeederMakeCommand;
use Illuminate\Support\Str;
use Sedehi\Artist\Console\Questions\SectionName;
use Sedehi\Artist\Console\Traits\CommandOptions;
use Sedehi\Artist\Console\Traits\Interactive;

class MakeSeeder extends SeederMakeCommand implements SectionName
{
    use CommandOptions,Interactive;

    protected function qualifyClass($name)
    {
        if ($this->option('section') == null) {
            return parent::qualifyClass($name);
        }

        $name = ltrim($name, '\\/');

        $rootNamespace = $this->rootNamespace();

        if (Str::startsWith($name, $rootNamespace)) {
            return $name;
        }

        $name = str_replace('/', '\\', $name);

        return $this->qualifyClass(
            $this->getDefaultNamespace(trim($rootNamespace, '\\')).'\\'.$name
        );
    }

    protected function getPath($name)
    {
        if ($this->option('section') == null) {
            return parent::getPath($name);
        }

        $name = Str::replaceFirst($this->rootNamespace(), '', $name);

        return $this->laravel['path'].'/'.str_replace('\\', '/', $name).'.php';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        $namespace = $rootNamespace.'\Http';
        if ($this->option('section') !== null) {
            $namespace .= '\Controllers\\'.Str::studly($this->option('section'));
        }

        return $namespace.'\database\seeds';
    }

    protected function getStub()
    {
        if ($this->option('section') == null) {
            return parent::getStub();
        }

        return __DIR__.'/stubs/seeder.stub';
    }
}
