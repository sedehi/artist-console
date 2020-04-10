<?php

namespace Sedehi\Artist\Console\Traits;

use Exception;
use ReflectionClass;
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
            $sectionName = $this->ask('Section name?');
            $this->input->setOption('section', $sectionName);
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
