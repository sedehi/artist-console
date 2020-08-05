<?php

namespace Sedehi\Artist\Console\Tests\Command;

use Sedehi\Artist\Console\Tests\SectionTestCase;

class MakeArtistResourceTest extends SectionTestCase
{
    /**
     * @return void
     * @test
     */
    public function make_artist_resource_test()
    {
        $path = app_path("{$this->sampleName}.php");

        $this->artisan('make:artist-resource', [
            'name'  => $this->sampleName,
        ]);

        $this->assertFileExists($path);

        $path = app_path('Http/Controllers/'.$this->sectionName."/{$this->sampleName}.php");

        $this->artisan('make:artist-resource', [
            'name'      => $this->sampleName,
            '--section' => $this->sectionName,
        ]);

        $this->assertFileExists($path);
    }

    /**
     * @return void
     * @test
     */
    public function interactive_option()
    {
        $path = app_path('Http/Controllers/'.$this->sectionName."/{$this->sampleName}.php");

        $this->artisan('make:artist-resource', [
            'name'  => $this->sampleName,
            '--in'  => true,
        ])->expectsQuestion('Enter section name: [optional]', $this->sectionName)
            ->assertExitCode(0);

        $this->assertFileExists($path);
    }
}
