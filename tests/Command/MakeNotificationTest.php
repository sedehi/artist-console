<?php

namespace Sedehi\Artist\Console\Tests\Command;

use Sedehi\Artist\Console\Tests\SectionTestCase;

class MakeNotificationTest extends SectionTestCase
{
    /**
     * @return void
     * @test
     */
    public function make_notification_with_section_option()
    {
        $path = app_path('Http/Controllers/'.$this->sectionName."/Notifications/{$this->sampleName}.php");

        $this->artisan('make:notification', [
            'name'      => $this->sampleName,
            '--section' => $this->sectionName,
        ]);

        $this->assertFileExists($path);
    }

    /**
     * @return void
     * @test
     */
    public function test_notification_command_interactive()
    {
        $this->artisan('make:notification', [
            'name'  => $this->sampleName,
            '--in'  => true,
        ])->expectsQuestion('Enter section name: [optional]', $this->sectionName)
            ->assertExitCode(0);
    }
}
