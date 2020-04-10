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
        $name = 'TestFactory';
        $path = app_path('Http/Controllers/'.$this->sectionName."/database/factories/{$name}.php");

        $this->artisan('make:factory', [
            'name'      => $name,
            '--section' => $this->sectionName
        ]);

        $this->assertFileExists($path);
    }
}
