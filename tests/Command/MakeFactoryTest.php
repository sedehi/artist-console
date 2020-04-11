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
            '--section' => $this->sectionName,
        ]);

        $this->assertFileExists($path);
    }

    /**
     * @return void
     * @test
     */
    public function test_factory_command_interactive()
    {
        $this->artisan('make:factory', [
            'name'  => $this->factoryName,
            '--in'  => true,
        ])->expectsQuestion('Enter section name: [optional]', $this->sectionName)
            ->expectsQuestion('Enter model name: [optional]', $this->modelName)
            ->assertExitCode(0);
    }
}
