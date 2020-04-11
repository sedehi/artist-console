<?php

namespace Sedehi\Artist\Console\Tests\Command;

use Sedehi\Artist\Console\Tests\SectionTestCase;

class InteractiveTest extends SectionTestCase
{
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
