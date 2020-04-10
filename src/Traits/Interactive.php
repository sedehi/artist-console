<?php

namespace Sedehi\Artist\Console\Traits;

use Exception;
use ReflectionClass;
use Sedehi\Artist\Console\Questions\ModelName;
use Sedehi\Artist\Console\Questions\SectionName;

trait Interactive
{
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
