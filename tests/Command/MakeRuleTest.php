<?php

namespace Sedehi\Artist\Console\Tests\Command;

use Sedehi\Artist\Console\Tests\SectionTestCase;

class MakeRuleTest extends SectionTestCase
{
    /**
     * @return void
     * @test
     */
    public function make_rule_with_section_option()
    {
        $path = app_path('Http/Controllers/'.$this->sectionName."/Rules/{$this->sampleName}.php");

        $this->artisan('make:rule', [
            'name'      => $this->sampleName,
            '--section' => $this->sectionName,
        ]);

        $this->assertFileExists($path);
    }

    /**
     * @return void
     * @test
     */
    public function test_rule_command_interactive()
    {
        $this->artisan('make:rule', [
            'name'  => $this->sampleName,
            '--in'  => true,
        ])->expectsQuestion('Enter section name: [optional]', $this->sectionName)
            ->assertExitCode(0);
    }
}
