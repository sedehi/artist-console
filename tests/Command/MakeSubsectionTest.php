<?php

namespace Sedehi\Artist\Console\Tests\Command;

use Sedehi\Artist\Console\Tests\SectionTestCase;

class MakeSubsectionTest extends SectionTestCase
{
    /**
     * @return void
     * @test
     */
    public function test_rule_command_interactive()
    {
        $modelClass = app()->getNamespace().'Http\\Controllers\\'.$this->sectionName."\\Models\\{$this->sampleName}";
        $adminRequestClass = app()->getNamespace().'Http\\Controllers\\'.$this->sectionName."\\Requests\\Admin\\{$this->sampleName}Request";
        $siteRequestClass = app()->getNamespace().'Http\\Controllers\\'.$this->sectionName."\\Requests\\Site\\{$this->sampleName}Request";
        $apiRequestClass = app()->getNamespace().'Http\\Controllers\\'.$this->sectionName."\\Requests\\Api\\V1\\{$this->sampleName}Request";

        $this->artisan('make:subsection', [
            'parent'    => $this->sectionName,
            'name'      => $this->sampleName,
        ])->expectsQuestion('Do you want to create model ? [y|n]', 'yes')
            ->expectsQuestion('Do you want to create admin controller ? [y|n]', 'yes')
            ->expectsQuestion('Do you want to upload picture in admin ? [y|n]', 'yes')
            ->expectsQuestion("A {$modelClass} model does not exist. Do you want to generate it?", 'yes')
            ->expectsQuestion("A {$adminRequestClass} Request does not exist. Do you want to generate it?", 'yes')
            ->expectsQuestion('Do you want to create site controller ? [y|n]', 'yes')
            ->expectsQuestion("A {$siteRequestClass} Request does not exist. Do you want to generate it?", 'yes')
            ->expectsQuestion('Do you want to create api controller ? [y|n]', 'yes')
            ->expectsQuestion("A {$apiRequestClass} Request does not exist. Do you want to generate it?", 'yes')
            ->expectsQuestion('Do you want to create factory ? [y|n]', 'yes')
            ->expectsQuestion('Do you want to create migration ? [y|n]', 'yes')
            ->expectsQuestion('What is table name?', 'yes')
            ->expectsQuestion('Do you want to create route ? [y|n]', 'yes')
            ->assertExitCode(0);

        $this->assertFileExists(
            app_path('Http/Controllers/'.$this->sectionName."/Models/{$this->sampleName}.php")
        );
        $this->assertFileExists(
            app_path('Http/Controllers/'.$this->sectionName."/Controllers/Admin/{$this->sampleName}Controller.php")
        );
        $this->assertFileExists(
            app_path('Http/Controllers/'.$this->sectionName."/Requests/Admin/{$this->sampleName}Request.php")
        );
        $this->assertFileExists(
            app_path('Http/Controllers/'.$this->sectionName."/Controllers/Site/{$this->sampleName}Controller.php")
        );
        $this->assertFileExists(
            app_path('Http/Controllers/'.$this->sectionName."/Requests/Site/{$this->sampleName}Request.php")
        );
        $this->assertFileExists(
            app_path('Http/Controllers/'.$this->sectionName."/Controllers/Api/V1/{$this->sampleName}Controller.php")
        );
        $this->assertFileExists(
            app_path('Http/Controllers/'.$this->sectionName."/Requests/Api/V1/{$this->sampleName}Request.php")
        );
        $this->assertFileExists(
            app_path('Http/Controllers/'.$this->sectionName."/database/factories/{$this->sampleName}Factory.php")
        );
        $this->assertFileExists(
            app_path('Http/Controllers/'.$this->sectionName."/routes/admin.php")
        );
        $this->assertFileExists(
            app_path('Http/Controllers/'.$this->sectionName."/routes/web.php")
        );
        $this->assertFileExists(
            app_path('Http/Controllers/'.$this->sectionName."/routes/api.php")
        );
    }
}
