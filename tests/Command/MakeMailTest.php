<?php

namespace Sedehi\Artist\Console\Tests\Command;

use Sedehi\Artist\Console\Tests\SectionTestCase;

class MakeMailTest extends SectionTestCase
{
    /**
     * @return void
     * @test
     */
    public function make_mail_with_section_option()
    {
        $path = app_path('Http/Controllers/'.$this->sectionName."/Mail/{$this->sampleName}.php");

        $this->artisan('make:mail', [
            'name'      => $this->sampleName,
            '--section' => $this->sectionName,
        ]);

        $this->assertFileExists($path);
    }

    /**
     * @return void
     * @test
     */
    public function test_mail_command_interactive()
    {
        $this->artisan('make:mail', [
            'name'  => $this->sampleName,
            '--in'  => true,
        ])->expectsQuestion('Enter section name: [optional]', $this->sectionName)
            ->assertExitCode(0);
    }
}
