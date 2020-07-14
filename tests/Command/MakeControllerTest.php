<?php

namespace Sedehi\Artist\Console\Tests\Command;

use Sedehi\Artist\Console\Tests\SectionTestCase;

class MakeControllerTest extends SectionTestCase
{
    /**
     * @return void
     * @test
     */
    public function make_controller_section_option()
    {
        $path = app_path("Http/Controllers/{$this->sampleName}.php");

        $this->artisan('make:controller', [
            'name'      => $this->sampleName,
        ]);

        $this->assertFileExists($path);

        $path = app_path('Http/Controllers/'.$this->sectionName."/Controllers/{$this->sampleName}.php");

        $requestClass = app()->getNamespace().'Http\\Controllers\\'.$this->sectionName."\\Requests\\{$this->sampleName}Request";
        $requestClassPath = app_path('Http/Controllers/'.$this->sectionName."/Requests/{$this->sampleName}Request.php");

        $this->artisan('make:controller', [
            'name'      => $this->sampleName,
            '--section' => $this->sectionName,
        ])->expectsConfirmation("A {$requestClass} Request does not exist. Do you want to generate it?", 'yes')
            ->assertExitCode(0);

        $this->assertFileExists($path);
        $this->assertFileExists($requestClassPath);
    }

    /**
     * @return void
     * @test
     */
    public function make_controller_class_type_option()
    {
        // admin type
        $path = app_path('Http/Controllers/'.$this->sectionName."/Controllers/Admin/{$this->sampleName}.php");

        $requestClass = app()->getNamespace().'Http\\Controllers\\'.$this->sectionName."\\Requests\\Admin\\{$this->sampleName}Request";

        $this->artisan('make:controller', [
            'name'      => $this->sampleName,
            '--section' => $this->sectionName,
            '--admin'   => true,
        ])->expectsConfirmation("A {$requestClass} Request does not exist. Do you want to generate it?", 'no')
            ->assertExitCode(0);

        $this->assertFileExists($path);

        // site type
        $path = app_path('Http/Controllers/'.$this->sectionName."/Controllers/Site/{$this->sampleName}.php");

        $requestClass = app()->getNamespace().'Http\\Controllers\\'.$this->sectionName."\\Requests\\Site\\{$this->sampleName}Request";

        $this->artisan('make:controller', [
            'name'      => $this->sampleName,
            '--section' => $this->sectionName,
            '--site'   => true,
        ])->expectsConfirmation("A {$requestClass} Request does not exist. Do you want to generate it?", 'no')
            ->assertExitCode(0);

        $this->assertFileExists($path);

        // api type without version
        $path = app_path('Http/Controllers/'.$this->sectionName."/Controllers/Api/{$this->sampleName}.php");

        $requestClass = app()->getNamespace().'Http\\Controllers\\'.$this->sectionName."\\Requests\\Api\\V1\\{$this->sampleName}Request";

        $this->artisan('make:controller', [
            'name'      => $this->sampleName,
            '--section' => $this->sectionName,
            '--api'   => true,
        ])->expectsConfirmation("A {$requestClass} Request does not exist. Do you want to generate it?", 'no')
            ->assertExitCode(0);

        $this->assertFileExists($path);

        // api type with version
        $path = app_path('Http/Controllers/'.$this->sectionName."/Controllers/Api/V2/{$this->sampleName}.php");

        $requestClass = app()->getNamespace().'Http\\Controllers\\'.$this->sectionName."\\Requests\\Api\\V2\\{$this->sampleName}Request";

        $this->artisan('make:controller', [
            'name'      => $this->sampleName,
            '--section' => $this->sectionName,
            '--api'   => true,
            '--api-version'   => 'v2',
        ])->expectsConfirmation("A {$requestClass} Request does not exist. Do you want to generate it?", 'no')
            ->assertExitCode(0);

        $this->assertFileExists($path);
    }

    /**
     * @return void
     * @test
     */
    public function model_is_required_if_crud_option_used()
    {
        $this->artisan('make:controller', [
            'name'      => $this->sampleName,
            '--section' => $this->sectionName,
            '--crud'    => true,
        ])->expectsOutput('You should specify model when using crud option')
            ->assertExitCode(0);
    }

    /**
     * @return void
     * @test
     */
    public function interactive_controller_types_and_section_option()
    {
        // controller types without section input
        $this->artisan('make:controller', [
            'name'  => $this->sampleName,
            '--in'  => true,
        ])->expectsQuestion('Enter section name: [optional]',null)
            ->expectsChoice('What type of controller do you want ?','invokable',['invokable','resource'])
            ->expectsChoice('What part this class belongs to ?','none',['admin','site','api','none'])
            ->expectsConfirmation('Show additional options for controller ?','yes')
            ->expectsConfirmation('Do you want to force create the controller class ?','yes')
            ->expectsConfirmation('Do you want to add custom views option for controller class ?','yes')
            ->assertExitCode(0);

        // controller types with section input
        $requestClass = app()->getNamespace().'Http\\Controllers\\'.$this->sectionName."\\Requests\\{$this->sampleName}Request";

        $this->artisan('make:controller', [
            'name'  => $this->sampleName,
            '--in'  => true,
        ])->expectsQuestion('Enter section name: [optional]',$this->sectionName)
            ->expectsChoice('What type of controller do you want ?','invokable',['invokable','resource','crud','upload'])
            ->expectsChoice('What part this class belongs to ?','none',['admin','site','api','none'])
            ->expectsConfirmation('Show additional options for controller ?','no')
            ->expectsConfirmation("A {$requestClass} Request does not exist. Do you want to generate it?", 'no')
            ->assertExitCode(0);
    }

    /**
     * @return void
     * @test
     */
    public function interactive_resource_controller_should_aks_for_parent_model_name()
    {
        $modelClass = app()->getNamespace().'Http\\Controllers\\'.$this->sectionName."\\Models\\{$this->sampleName}";
        $requestClass = app()->getNamespace().'Http\\Controllers\\'.$this->sectionName."\\Requests\\{$this->sampleName}Request";

        $this->artisan('make:controller', [
            'name'  => $this->sampleName,
            '--in'  => true,
        ])->expectsQuestion('Enter section name: [optional]',$this->sectionName)
            ->expectsChoice('What type of controller do you want ?','resource',['invokable','resource','crud','upload'])
            ->expectsConfirmation('Add parent model to resource controller ?','yes')
            ->expectsQuestion('Enter parent model name:',$this->sampleName)
            ->expectsChoice('What part this class belongs to ?','none',['admin','site','api','none'])
            ->expectsConfirmation('Show additional options for controller ?','no')
            ->expectsConfirmation("A {$modelClass} model does not exist. Do you want to generate it?")
            ->expectsConfirmation("A {$requestClass} Request does not exist. Do you want to generate it?")
            ->assertExitCode(0);
    }

    /**
     * @return void
     * @test
     */
    public function interactive_crud_controller_should_aks_for_model_name()
    {
        $modelClass = app()->getNamespace().'Http\\Controllers\\'.$this->sectionName."\\Models\\{$this->sampleName}";
        $requestClass = app()->getNamespace().'Http\\Controllers\\'.$this->sectionName."\\Requests\\{$this->sampleName}Request";

        $this->artisan('make:controller', [
            'name'  => $this->sampleName,
            '--in'  => true,
        ])->expectsQuestion('Enter section name: [optional]',$this->sectionName)
            ->expectsChoice('What type of controller do you want ?','crud',['invokable','resource','crud','upload'])
            ->expectsQuestion('Enter model name',$this->sampleName)
            ->expectsChoice('What part this class belongs to ?','none',['admin','site','api','none'])
            ->expectsConfirmation('Show additional options for controller ?','no')
            ->expectsConfirmation("A {$modelClass} model does not exist. Do you want to generate it?")
            ->expectsConfirmation("A {$requestClass} Request does not exist. Do you want to generate it?")
            ->assertExitCode(0);
    }

    /**
     * @return void
     * @test
     */
    public function interactive_upload_controller_should_aks_for_model_name()
    {
        $modelClass = app()->getNamespace().'Http\\Controllers\\'.$this->sectionName."\\Models\\{$this->sampleName}";
        $requestClass = app()->getNamespace().'Http\\Controllers\\'.$this->sectionName."\\Requests\\{$this->sampleName}Request";

        $this->artisan('make:controller', [
            'name'  => $this->sampleName,
            '--in'  => true,
        ])->expectsQuestion('Enter section name: [optional]',$this->sectionName)
            ->expectsChoice('What type of controller do you want ?','upload',['invokable','resource','crud','upload'])
            ->expectsQuestion('Enter model name',$this->sampleName)
            ->expectsChoice('What part this class belongs to ?','none',['admin','site','api','none'])
            ->expectsConfirmation('Show additional options for controller ?','no')
            ->expectsConfirmation("A {$modelClass} model does not exist. Do you want to generate it?")
            ->expectsConfirmation("A {$requestClass} Request does not exist. Do you want to generate it?")
            ->assertExitCode(0);
    }
}
