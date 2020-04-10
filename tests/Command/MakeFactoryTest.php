<?php

namespace Sedehi\Artist\Console\Tests\Command;

use Sedehi\Artist\Console\Tests\SectionTestCase;

class MakeFactoryTest extends SectionTestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testSectionFactory()
    {
        $name = 'TestFactory';
        $path = app_path('Http/Controllers/'.$this->sectionName."/database/factories/{$name}.php");

        $this->artisan('make:factory', [
            'name'      => $name,
            '--section' => $this->sectionName
        ])->expectsOutput('Factory created successfully.');

        $this->assertFileExists($path);
    }
}
