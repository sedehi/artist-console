<?php

namespace Sedehi\Artist\Console\Tests\Command;

use Sedehi\Artist\Console\Tests\SectionTestCase;

class MakeRequestTest extends SectionTestCase
{
    /**
     * @return void
     * @test
     */
    public function make_request_section_option()
    {
        $path = app_path("Http/Requests/{$this->sampleName}.php");

        $this->artisan('make:request', [
            'name'      => $this->sampleName,
        ]);

        $this->assertFileExists($path);

        $path = app_path('Http/Controllers/'.$this->sectionName."/Requests/{$this->sampleName}.php");

        $this->artisan('make:request', [
            'name'      => $this->sampleName,
            '--section' => $this->sectionName,
        ]);

        $this->assertFileExists($path);
    }

    /**
     * @return void
     * @test
     */
    public function make_request_class_type_option()
    {
        // admin type
        $path = app_path('Http/Controllers/'.$this->sectionName."/Requests/Admin/{$this->sampleName}.php");

        $this->artisan('make:request', [
            'name'      => $this->sampleName,
            '--section' => $this->sectionName,
            '--admin'   => true,
        ]);

        $this->assertFileExists($path);

        // site type
        $path = app_path('Http/Controllers/'.$this->sectionName."/Requests/Site/{$this->sampleName}.php");

        $this->artisan('make:request', [
            'name'      => $this->sampleName,
            '--section' => $this->sectionName,
            '--site'   => true,
        ]);

        $this->assertFileExists($path);

        // api type without version
        $path = app_path('Http/Controllers/'.$this->sectionName."/Requests/Api/{$this->sampleName}.php");

        $this->artisan('make:request', [
            'name'      => $this->sampleName,
            '--section' => $this->sectionName,
            '--api'   => true,
        ]);

        $this->assertFileExists($path);

        // api type with version
        $path = app_path('Http/Controllers/'.$this->sectionName."/Requests/Api/V2/{$this->sampleName}.php");

        $this->artisan('make:request', [
            'name'      => $this->sampleName,
            '--section' => $this->sectionName,
            '--api'   => true,
            '--request-version'   => 'v2',
        ]);

        $this->assertFileExists($path);
    }

    /**
     * @return void
     * @test
     */
    public function test_request_command_interactive()
    {
        $this->artisan('make:request', [
            'name'  => $this->sampleName,
            '--in'  => true,
        ])->expectsQuestion('Enter section name: [optional]', $this->sectionName)
            ->expectsQuestion('Create admin request ?', 'y')
            ->expectsQuestion('Create site request ?', 'y')
            ->expectsQuestion('Create api request ?', 'y')
            ->expectsQuestion('What is the api version ?', 'v3')
            ->assertExitCode(0);

        $this->assertFileExists(
            app_path('Http/Controllers/'.$this->sectionName."/Requests/Admin/{$this->sampleName}.php")
        );
        $this->assertFileExists(
            app_path('Http/Controllers/'.$this->sectionName."/Requests/Site/{$this->sampleName}.php")
        );
        $this->assertFileExists(
            app_path('Http/Controllers/'.$this->sectionName."/Requests/Api/V3/{$this->sampleName}.php")
        );
    }
}
