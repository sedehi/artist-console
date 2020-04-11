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
        $path = app_path('Http/Controllers/'.$this->sectionName."/Events/{$this->eventName}.php");

        $this->artisan('make:event', [
            'name'      => $this->eventName,
            '--section' => $this->sectionName,
        ]);

        $this->assertFileExists($path);
    }
}
