<?php

namespace Sedehi\Artist\Console\Traits;

use Symfony\Component\Console\Input\InputOption;

trait CommandOptions
{
    protected function getOptions()
    {
        $options = parent::getOptions();
        $options = array_merge($options, [
            ['section', null, InputOption::VALUE_OPTIONAL, 'The name of the section'],
        ]);

        return $options;
    }
}
