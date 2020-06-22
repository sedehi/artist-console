<?php

namespace Sedehi\Artist\Console\Tests\Command;

use Sedehi\Artist\Console\Tests\SectionTestCase;

class MakeObserverTest extends SectionTestCase
{
    /**
     * @return void
     * @test
     */
    public function make_observer_with_section_option()
    {
        $path = app_path('Http/Controllers/'.$this->sectionName."/Observers/{$this->sampleName}.php");

        $this->artisan('make:observer', [
            'name'      => $this->sampleName,
            '--section' => $this->sectionName,
        ]);

        $this->assertFileExists($path);
    }

    /**
     * @return void
     * @test
     */
    public function test_observer_command_interactive()
    {
        $this->artisan('make:observer', [
            'name'  => $this->sampleName,
            '--in'  => true,
        ])->expectsQuestion('Enter section name: [optional]', $this->sectionName)
            ->expectsQuestion('Enter model name: [optional]', $this->sampleName)
            ->assertExitCode(0);
    }
}
