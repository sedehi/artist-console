<?php

namespace Sedehi\Artist\Console\Tests\Command;

use Sedehi\Artist\Console\Tests\SectionTestCase;

class MakeResourceTest extends SectionTestCase
{
    /**
     * @return void
     * @test
     */
    public function make_resource_section_option()
    {
        $path = app_path("Http/Resources/{$this->sampleName}.php");

        $this->artisan('make:resource', [
            'name'      => $this->sampleName,
        ]);

        $this->assertFileExists($path);

        $path = app_path('Http/Controllers/'.$this->sectionName."/Resources/{$this->sampleName}.php");

        $this->artisan('make:resource', [
            'name'      => $this->sampleName,
            '--section' => $this->sectionName,
        ]);

        $this->assertFileExists($path);
    }

    /**
     * @return void
     * @test
     */
    public function make_resource_api_version_option()
    {
        $path = app_path('Http/Controllers/'.$this->sectionName."/Resources/V2/{$this->sampleName}.php");

        $this->artisan('make:resource', [
            'name'      => $this->sampleName,
            '--section' => $this->sectionName,
            '--api-version' => 'v2',
        ]);

        $this->assertFileExists($path);
    }

    /**
     * @return void
     * @test
     */
    public function test_resource_command_interactive()
    {
        $this->artisan('make:resource', [
            'name'  => $this->sampleName,
            '--in'  => true,
        ])->expectsQuestion('Enter section name: [optional]', $this->sectionName)
            ->expectsQuestion('What is the api version ?', 'v3')
            ->expectsQuestion('Do you want to make a resource collection class ?', 'yes')
            ->assertExitCode(0);

        $this->assertFileExists(
            app_path('Http/Controllers/'.$this->sectionName."/Resources/V3/{$this->sampleName}.php")
        );
    }
}
