<?php

namespace Sedehi\Artist\Console\Command;

use Illuminate\Routing\Console\ControllerMakeCommand;
use Illuminate\Support\Str;
use Sedehi\Artist\Console\Questions\SectionName;
use Sedehi\Artist\Console\Traits\Interactive;
use Symfony\Component\Console\Input\InputOption;

class MakeController extends ControllerMakeCommand implements SectionName
{
    use Interactive;

    protected function getOptions()
    {
        $options = parent::getOptions();
        $options = array_merge($options, [
            ['section', null, InputOption::VALUE_OPTIONAL, 'The name of the section'],
            ['in', false, InputOption::VALUE_NONE, 'Interactive mode'],
            ['crud', null, InputOption::VALUE_NONE, 'Generate a crud controller class'],
            ['upload', null, InputOption::VALUE_NONE, 'Generate an upload controller class'],
            ['site', null, InputOption::VALUE_NONE, 'Generate a site controller class'],
            ['admin', null, InputOption::VALUE_NONE, 'Generate an admin controller class'],
            ['api-version', null, InputOption::VALUE_OPTIONAL, 'Set Api version'],
            ['custom-views', null, InputOption::VALUE_NONE, 'Generate views from old stubs'],
        ]);

        return $options;
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        $namespace = $rootNamespace.'\Http\Controllers';
        if ($this->option('section')) {
            $namespace .= '\\'.Str::studly($this->option('section')).'\\Controllers';
        }
        if ($this->option('site')) {
            $namespace .= '\\Site';
        }
        if ($this->option('admin')) {
            $namespace .= '\\Admin';
        }
        if ($this->option('api')) {
            $namespace .= '\\Api';
            if (!is_null($this->option('api-version'))) {
                $namespace .= '\\'.Str::studly($this->option('api-version'));
            }
        }

        return $namespace;
    }

    protected function getStub()
    {
        if ($this->option('crud') && $this->option('model')) {
            if ($this->option('custom-views')) {
                return __DIR__.'/stubs/controller-crud.stub';
            }
            return __DIR__.'/stubs/controller-crud-dynamic.stub';
        }
        if ($this->option('upload') && $this->option('model')) {
            if ($this->option('custom-views')) {
                return __DIR__.'/stubs/controller-upload.stub';
            }

            return __DIR__.'/stubs/controller-upload-dynamic.stub';
        }

        return parent::getStub();
    }

    protected function buildClass($name)
    {
        $controllerNamespace = $this->getNamespace($name);

        $replace = [];

        if ($this->option('parent')) {
            $replace = $this->buildParentReplacements();
        }

        if ($this->option('model')) {
            $replace = $this->buildModelReplacements($replace);
        }

        if ($this->option('section')) {
            $replace = $this->buildSectionReplacements($replace);
            $replace = $this->buildRequestReplacements($replace);
            $replace = $this->buildViewsReplacements($replace);
            $replace = $this->buildActionReplacements($replace);
        }

        $replace["use {$controllerNamespace}\Controller;\n"] = '';

        return str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name)
        );
    }

    protected function buildParentReplacements()
    {
        $parentModelClass = $this->parseModel($this->option('parent'));

        if ($this->option('section')) {
            $parentModelClass = $this->laravel->getNamespace().'Http\\Controllers\\'.Str::studly($this->option('section')).'\\Models\\'.Str::studly($this->option('parent'));
        }

        if (! class_exists($parentModelClass)) {
            if ($this->confirm("A {$parentModelClass} model does not exist. Do you want to generate it?", true)) {
                $this->call('make:model', [
                    'name' => $parentModelClass,
                    '--section' => $this->option('section'),
                ]);
            }
        }

        return [
            'ParentDummyFullModelClass' => $parentModelClass,
            '{{ namespacedParentModel }}' => $parentModelClass,
            '{{namespacedParentModel}}' => $parentModelClass,
            'ParentDummyModelClass' => class_basename($parentModelClass),
            '{{ parentModel }}' => class_basename($parentModelClass),
            '{{parentModel}}' => class_basename($parentModelClass),
            'ParentDummyModelVariable' => lcfirst(class_basename($parentModelClass)),
            '{{ parentModelVariable }}' => lcfirst(class_basename($parentModelClass)),
            '{{parentModelVariable}}' => lcfirst(class_basename($parentModelClass)),
        ];
    }

    protected function buildModelReplacements(array $replace)
    {
        $modelClass = $this->parseModel($this->option('model'));

        if ($this->option('section')) {
            $modelClass = $this->laravel->getNamespace().'Http\\Controllers\\'.Str::studly($this->option('section')).'\\Models\\'.Str::studly($this->option('model'));
        }
        if (!class_exists($modelClass)) {
            if ($this->confirm("A {$modelClass} model does not exist. Do you want to generate it?", true)) {
                $this->call('make:model', [
                    'name'      => $modelClass,
                    '--section' => $this->option('section'),
                ]);
            }
        }

        return array_merge($replace, [
            'DummyFullModelClass' => $modelClass,
            '{{ namespacedModel }}' => $modelClass,
            '{{namespacedModel}}' => $modelClass,
            'DummyModelClass' => class_basename($modelClass),
            '{{ model }}' => class_basename($modelClass),
            '{{model}}' => class_basename($modelClass),
            'DummyModelVariable' => lcfirst(class_basename($modelClass)),
            '{{ modelVariable }}' => lcfirst(class_basename($modelClass)),
            '{{modelVariable}}' => lcfirst(class_basename($modelClass)),
        ]);
    }
}
