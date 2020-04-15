<?php

namespace Sedehi\Artist\Console\Tests\Command;

use Sedehi\Artist\Console\Tests\SectionTestCase;

class MakeEventTest extends SectionTestCase
{
    /**
     * @return void
     * @test
     */
    public function make_event_with_section_option()
    {
        $path = app_path('Http/Controllers/'.$this->sectionName."/Events/{$this->sampleName}.php");

        $this->artisan('make:event', [
            'name'      => $this->sampleName,
            '--section' => $this->sectionName,
        ]);

        $this->assertFileExists($path);
    }

    /**
     * @return void
     * @test
     */
    public function test_event_command_interactive()
    {
        $this->artisan('make:event', [
            'name'  => $this->sampleName,
            '--in'  => true,
        ])->expectsQuestion('Enter section name: [optional]', $this->sectionName)
            ->assertExitCode(0);
    }
}
