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
        $path = app_path('Http/Controllers/'.$this->sectionName."/{$this->sampleName}.php");

        $this->artisan('make:artist-resource', [
            'section'   => $this->sectionName,
            'name'      => $this->sampleName,
        ]);

        $this->assertFileExists($path);
    }
}
