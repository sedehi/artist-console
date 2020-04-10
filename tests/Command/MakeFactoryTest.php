<?php

namespace Sedehi\Artist\Console\Tests\Command;

use Sedehi\Artist\Console\Tests\SectionTestCase;

class MakeFactoryTest extends SectionTestCase
{
    /**
     * @return void
     * @test
     */
    public function make_factory_with_section_option()
    {
        $path = app_path('Http/Controllers/'.$this->sectionName."/database/factories/{$this->factoryName}.php");

        $this->artisan('make:factory', [
            'name'      => $this->factoryName,
            '--section' => $this->sectionName
        ]);

        $this->assertFileExists($path);
    }
}
