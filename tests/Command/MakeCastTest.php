<?php

namespace Sedehi\Artist\Console\Tests\Command;

use Sedehi\Artist\Console\Tests\SectionTestCase;

class MakeCastTest extends SectionTestCase
{
    /**
     * @return void
     * @test
     */
    public function make_cast_with_section_option()
    {
        $path = app_path('Http/Controllers/'.$this->sectionName."/Casts/{$this->sampleName}.php");

        $this->artisan('make:cast', [
            'name'      => $this->sampleName,
            '--section' => $this->sectionName,
        ]);

        $this->assertFileExists($path);
    }

    /**
     * @return void
     * @test
     */
    public function test_cast_command_interactive()
    {
        $this->artisan('make:cast', [
            'name'  => $this->sampleName,
            '--in'  => true,
        ])->expectsQuestion('Enter section name: [optional]', $this->sectionName)
            ->assertExitCode(0);
    }
}
