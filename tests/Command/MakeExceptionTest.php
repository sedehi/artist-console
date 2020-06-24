<?php

namespace Sedehi\Artist\Console\Tests\Command;

use Sedehi\Artist\Console\Tests\SectionTestCase;

class MakeExceptionTest extends SectionTestCase
{
    /**
     * @return void
     * @test
     */
    public function make_exception_with_section_option()
    {
        $path = app_path('Http/Controllers/'.$this->sectionName."/Exceptions/{$this->sampleName}.php");

        $this->artisan('make:exception', [
            'name'      => $this->sampleName,
            '--section' => $this->sectionName,
        ]);

        $this->assertFileExists($path);
    }

    /**
     * @return void
     * @test
     */
    public function test_exception_command_interactive()
    {
        $this->artisan('make:exception', [
            'name'  => $this->sampleName,
            '--in'  => true,
        ])->expectsQuestion('Enter section name: [optional]', $this->sectionName)
            ->assertExitCode(0);
    }
}
