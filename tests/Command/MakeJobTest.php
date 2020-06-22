<?php

namespace Sedehi\Artist\Console\Tests\Command;

use Sedehi\Artist\Console\Tests\SectionTestCase;

class MakeJobTest extends SectionTestCase
{
    /**
     * @return void
     * @test
     */
    public function make_job_with_section_option()
    {
        $path = app_path('Http/Controllers/'.$this->sectionName."/Jobs/{$this->sampleName}.php");

        $this->artisan('make:job', [
            'name'      => $this->sampleName,
            '--section' => $this->sectionName,
        ]);

        $this->assertFileExists($path);
    }

    /**
     * @return void
     * @test
     */
    public function test_job_command_interactive()
    {
        $this->artisan('make:job', [
            'name'  => $this->sampleName,
            '--in'  => true,
        ])->expectsQuestion('Enter section name: [optional]', $this->sectionName)
            ->assertExitCode(0);
    }
}
