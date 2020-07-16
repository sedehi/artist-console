<?php

namespace Sedehi\Artist\Console\Traits;

use Exception;
use ReflectionClass;
use Sedehi\Artist\Console\Questions\ApiVersion;
use Sedehi\Artist\Console\Questions\ClassType;
use Sedehi\Artist\Console\Questions\ClassTypeMultiple;
use Sedehi\Artist\Console\Questions\ControllerType;
use Sedehi\Artist\Console\Questions\EventlName;
use Sedehi\Artist\Console\Questions\ModelName;
use Sedehi\Artist\Console\Questions\ParentModelName;
use Sedehi\Artist\Console\Questions\ResourceCollection;
use Sedehi\Artist\Console\Questions\SectionName;

trait Interactive
{
    private $needApiVersion = false;
    private $needModel = false;
    private $needParentModel = false;

    public function handle()
    {
        $this->interactive();
        parent::handle();
    }

    protected function interactive()
    {
        $in = $this->option('in');
        if ($in === 'false') {
            $in = false;
        }
        if ($in === null) {
            $in = true;
        }
        if (! $in) {
            return false;
        }

        if ($this->implements(SectionName::class)) {
            $sectionName = $this->ask('Enter section name: [optional]');
            $this->input->setOption('section', $sectionName);
        }

        // controller related checks
        if ($this->implements(ControllerType::class)) {
            $choices = [
                'invokable',
                'resource',
            ];
            if ($this->option('section')) {
                $choices = array_merge($choices, [
                    'crud',
                    'upload',
                ]);
            }
            $controllerType = $this->choice('What type of controller do you want ?', $choices);
            $this->input->setOption($controllerType, true);
        }
        if (isset($controllerType)) {
            switch ($controllerType) {
                case 'crud':
                case 'upload':
                    $this->needModel = true;
                    break;
                case 'resource':
                    if ($this->confirm('Add parent model to resource controller ?')) {
                        $this->needParentModel = true;
                    }
                    break;
            }
        }

        if ($this->implements(ModelName::class) || $this->needModel) {
            $modelName = null;

            if ($this->needModel) {
                while (! $modelName) {
                    $modelName = $this->ask('Enter model name');
                }
            } else {
                $modelName = $this->ask('Enter model name: [optional]');
            }

            $this->input->setOption('model', $modelName);
        }
        if ($this->implements(ParentModelName::class) || $this->needParentModel) {
            $parentModelName = $this->ask('Enter parent model name:');
            $this->input->setOption('parent', $parentModelName);
        }
        if ($this->implements(EventlName::class)) {
            $eventName = $this->ask('Enter event name: [optional]');
            $this->input->setOption('event', $eventName);
        }
        if ($this->implements(ClassType::class)) {
            $classType = $this->choice('What part this class belongs to ?', [
                'admin',
                'site',
                'api',
                'none',
            ]);
            if ($classType !== 'none') {
                $this->input->setOption($classType, true);
                if ($classType == 'api') {
                    $this->needApiVersion = true;
                }
            }
        }
        if ($this->implements(ClassTypeMultiple::class)) {
            $createAdminType = $this->confirm('Create class for admin ?');
            $this->input->setOption('admin', $createAdminType);

            $createSiteType = $this->confirm('Create class for site ?');
            $this->input->setOption('site', $createSiteType);

            $createApiType = $this->confirm('Create class for api ?');
            $this->input->setOption('api', $createApiType);
            $this->needApiVersion = $createApiType;
        }
        if ($this->implements(ApiVersion::class) || $this->needApiVersion) {
            $apiVersion = $this->ask('What is the api version ?', 'v1');
            $this->input->setOption('api-version', $apiVersion);
        }
        if ($this->implements(ResourceCollection::class)) {
            $collectionType = $this->confirm('Do you want to make a resource collection class ?');
            $this->input->setOption('collection', $collectionType);
        }

        // additional controller related options
        if (isset($controllerType)) {
            if ($this->confirm('Show additional options for controller ?')) {
                $force = $this->confirm('Do you want to force create the controller class ?');
                $this->input->setOption('force', $force);

                $customViews = $this->confirm('Do you want to add custom views option for controller class ?');
                $this->input->setOption('custom-views', $customViews);
            }
        }
    }

    protected function implements($class)
    {
        try {
            return (new ReflectionClass($this))->implementsInterface($class);
        } catch (Exception $e) {
            return false;
        }
    }
}
