<?php

namespace Sedehi\Artist\Console\Tests\Command;

use Sedehi\Artist\Console\Tests\SectionTestCase;

class MakeTest extends SectionTestCase
{
    /**
     * @return void
     * @test
     */
    public function make_test_section_option()
    {
        $path = base_path("tests/Feature/{$this->sampleName}.php");

        $this->artisan('make:test', [
            'name'      => $this->sampleName,
        ]);

        $this->assertFileExists($path);

        $path = app_path('Http/Controllers/'.$this->sectionName."/tests/Feature/{$this->sampleName}.php");

        $this->artisan('make:test', [
            'name'      => $this->sampleName,
            '--section' => $this->sectionName,
        ]);

        $this->assertFileExists($path);
    }
    /**
     * @return void
     * @test
     */
    public function make_test_unit_option()
    {
        $path = app_path('Http/Controllers/'.$this->sectionName."/tests/Unit/{$this->sampleName}.php");

        $this->artisan('make:test', [
            'name'      => $this->sampleName,
            '--section' => $this->sectionName,
            '--unit'    => true,
        ]);

        $this->assertFileExists($path);
    }

    /**
     * @return void
     * @test
     */
    public function make_test_class_type_option()
    {
        // admin type
        $path = app_path('Http/Controllers/'.$this->sectionName."/tests/Admin/Feature/{$this->sampleName}.php");

        $this->artisan('make:test', [
            'name'      => $this->sampleName,
            '--section' => $this->sectionName,
            '--admin'   => true,
        ]);

        $this->assertFileExists($path);

        // site type
        $path = app_path('Http/Controllers/'.$this->sectionName."/tests/Site/Feature/{$this->sampleName}.php");

        $this->artisan('make:test', [
            'name'      => $this->sampleName,
            '--section' => $this->sectionName,
            '--site'   => true,
        ]);

        $this->assertFileExists($path);

        // api type without version
        $path = app_path('Http/Controllers/'.$this->sectionName."/tests/Api/V1/Feature/{$this->sampleName}.php");

        $this->artisan('make:test', [
            'name'      => $this->sampleName,
            '--section' => $this->sectionName,
            '--api'   => true,
        ]);

        $this->assertFileExists($path);

        // api type with version
        $path = app_path('Http/Controllers/'.$this->sectionName."/tests/Api/V2/Feature/{$this->sampleName}.php");

        $this->artisan('make:test', [
            'name'      => $this->sampleName,
            '--section' => $this->sectionName,
            '--api'   => true,
            '--api-version'   => 'v2',
        ]);

        $this->assertFileExists($path);
    }

    /**
     * @return void
     * @test
     */
    public function model_and_controller_is_required_if_section_and_crud_option_used()
    {
        $this->artisan('make:test', [
            'name'      => $this->sampleName,
            '--section' => $this->sectionName,
            '--crud'    => true,
        ])->expectsOutput('You should specify model name if using crud option')
            ->assertExitCode(0);

        $this->artisan('make:test', [
            'name'      => $this->sampleName,
            '--section' => $this->sectionName,
            '--crud'    => true,
            '--model'   => $this->sampleName,
        ])->expectsOutput('You should specify controller name if using crud option')
            ->assertExitCode(0);
    }

    /**
     * @return void
     * @test
     */
    public function interactive_no_section()
    {
        $path = base_path("tests/Unit/{$this->sampleName}.php");

        $this->artisan('make:test', [
            'name'  => $this->sampleName,
            '--in'  => true,
        ])->expectsQuestion('Enter section name: [optional]', null)
            ->expectsQuestion('Do you want to create a unit test class ?', 'yes')
            ->assertExitCode(0);

        $this->assertFileExists($path);
    }

    /**
     * @return void
     * @test
     */
    public function interactive_with_section_and_crud()
    {
        $path = app_path('Http/Controllers/'.$this->sectionName."/tests/Admin/Unit/{$this->sampleName}.php");

        $this->artisan('make:test', [
            'name'  => $this->sampleName,
            '--in'  => true,
        ])->expectsQuestion('Enter section name: [optional]', $this->sectionName)
            ->expectsQuestion('Do you want to create a unit test class ?', 'yes')
            ->expectsQuestion('Do you want to create a crud test ?', 'yes')
            ->expectsQuestion('Enter controller name', $this->sampleName)
            ->expectsQuestion('Enter model name', $this->sampleName)
            ->expectsQuestion('What part this class belongs to ?', 'admin')
            ->assertExitCode(0);

        $this->assertFileExists($path);
    }
}
