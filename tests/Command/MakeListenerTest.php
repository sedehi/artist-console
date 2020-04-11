<?php

namespace Sedehi\Artist\Console\Tests\Command;

use Sedehi\Artist\Console\Tests\SectionTestCase;

class MakeListenerTest extends SectionTestCase
{
    /**
     * @return void
     * @test
     */
    public function make_listener_with_section_option()
    {
        $path = app_path('Http/Controllers/'.$this->sectionName."/Listeners/{$this->listenerName}.php");

        $this->artisan('make:listener', [
            'name'      => $this->listenerName,
            '--section' => $this->sectionName,
        ]);

        $this->assertFileExists($path);
    }

    /**
     * @return void
     * @test
     */
    public function test_listener_command_interactive()
    {
        $this->artisan('make:listener', [
            'name'  => $this->listenerName,
            '--in'  => true,
        ])->expectsQuestion('Enter section name: [optional]', $this->sectionName)
            ->expectsQuestion('Enter event name: [optional]', $this->eventName)
            ->assertExitCode(0);
    }
}
