<?php

namespace Sedehi\Artist\Console\Tests\Command;

use Sedehi\Artist\Console\Tests\SectionTestCase;

class MakeCommandTest extends SectionTestCase
{
    /**
     * @return void
     * @test
     */
    public function make_command_with_section_option()
    {
        $path = app_path('Http/Controllers/'.$this->sectionName."/Commands/{$this->sampleName}.php");

        $this->artisan('make:command', [
            'name'      => $this->sampleName,
            '--section' => $this->sectionName,
        ]);

        $this->assertFileExists($path);
    }

    /**
     * @return void
     * @test
     */
    public function test_make_command_interactive()
    {
        $this->artisan('make:command', [
            'name'  => $this->sampleName,
            '--in'  => true,
        ])->expectsQuestion('Enter section name: [optional]', $this->sectionName)
            ->assertExitCode(0);
    }
}
