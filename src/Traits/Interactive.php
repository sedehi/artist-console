<?php

namespace Sedehi\Artist\Console\Traits;

use Exception;
use ReflectionClass;
use Sedehi\Artist\Console\Questions\ApiVersion;
use Sedehi\Artist\Console\Questions\ClassType;
use Sedehi\Artist\Console\Questions\EventlName;
use Sedehi\Artist\Console\Questions\ModelName;
use Sedehi\Artist\Console\Questions\SectionName;

trait Interactive
{
    private $needApiVersion = false;

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
        if ($this->implements(ModelName::class)) {
            $modelName = $this->ask('Enter model name: [optional]');
            $this->input->setOption('model', $modelName);
        }
        if ($this->implements(EventlName::class)) {
            $eventName = $this->ask('Enter event name: [optional]');
            $this->input->setOption('event', $eventName);
        }
        if ($this->implements(ClassType::class)) {
            $createAdminType = $this->confirm('Create admin request ?');
            $this->input->setOption('admin', $createAdminType);

            $createSiteType = $this->confirm('Create site request ?');
            $this->input->setOption('site', $createSiteType);

            $createApiType = $this->confirm('Create api request ?');
            $this->input->setOption('api', $createApiType);
            $this->needApiVersion = $createApiType;
        }
        if ($this->implements(ApiVersion::class) || $this->needApiVersion) {
            $apiVersion = $this->ask('What is the api version ?', 'v1');
            $this->input->setOption('api-version', $apiVersion);
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
