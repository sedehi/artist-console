<?php

namespace Sedehi\Artist\Console\Tests\Command;

use Sedehi\Artist\Console\Tests\SectionTestCase;

class MakeSeederTest extends SectionTestCase
{
    /**
     * @return void
     * @test
     */
    public function make_seeder_with_section_option()
    {
        $path = app_path('Http/Controllers/'.$this->sectionName."/database/seeds/{$this->sampleName}.php");

        $this->artisan('make:seeder', [
            'name'      => $this->sampleName,
            '--section' => $this->sectionName,
        ]);

        $this->assertFileExists($path);
    }

    /**
     * @return void
     * @test
     */
    public function make_seeder_without_section_option()
    {
        $path = database_path("seeds/{$this->sampleName}.php");

        $this->artisan('make:seeder', [
            'name'  => $this->sampleName,
        ]);

        $this->assertFileExists($path);
    }

    /**
     * @return void
     * @test
     */
    public function test_seeder_command_interactive()
    {
        $this->artisan('make:seeder', [
            'name'  => $this->sampleName,
            '--in'  => true,
        ])->expectsQuestion('Enter section name: [optional]', $this->sectionName)
            ->assertExitCode(0);
    }
}
