<?php

namespace Sedehi\Artist\Console\Tests\Command;

use Sedehi\Artist\Console\Tests\SectionTestCase;

class MakeChannelTest extends SectionTestCase
{
    /**
     * @return void
     * @test
     */
    public function make_channel_with_section_option()
    {
        $path = app_path('Http/Controllers/'.$this->sectionName."/Channels/{$this->sampleName}.php");

        $this->artisan('make:channel', [
            'name'      => $this->sampleName,
            '--section' => $this->sectionName,
        ]);

        $this->assertFileExists($path);
    }

    /**
     * @return void
     * @test
     */
    public function test_channel_command_interactive()
    {
        $this->artisan('make:channel', [
            'name'  => $this->sampleName,
            '--in'  => true,
        ])->expectsQuestion('Enter section name: [optional]', $this->sectionName)
            ->assertExitCode(0);
    }
}
