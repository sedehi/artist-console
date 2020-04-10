<?php

namespace Sedehi\Artist\Console\Command;

use Illuminate\Foundation\Console\ModelMakeCommand;
use Illuminate\Support\Str;
use Sedehi\Artist\Console\Questions\SectionName;
use Sedehi\Artist\Console\Traits\CommandOptions;
use Sedehi\Artist\Console\Traits\Interactive;

class MakeModel extends ModelMakeCommand implements SectionName
{
    use CommandOptions,Interactive;


    protected function getDefaultNamespace($rootNamespace)
    {
        if ($this->option('section') !== null) {
            return $rootNamespace.'\Http\Controllers\\'.Str::studly($this->option('section')).'\\Models';
        }
        return $rootNamespace;
    }

    protected function createFactory()
    {
        $factory = Str::studly(class_basename($this->argument('name')));
        $this->call('make:factory', [
            'name'      => "{$factory}Factory",
            '--model'   => $this->qualifyClass($this->getNameInput()),
            '--section' => Str::studly($this->option('section')),
        ]);
    }

    protected function createMigration()
    {
        $table = Str::snake(Str::pluralStudly(class_basename($this->argument('name'))));
        if ($this->option('pivot')) {
            $table = Str::singular($table);
        }
        $this->call('make:migration', [
            'name'      => "create_{$table}_table",
            '--create'  => $table,
            '--section' => Str::studly($this->option('section')),
        ]);
    }
}
