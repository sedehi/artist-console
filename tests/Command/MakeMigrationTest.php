<?php

namespace Sedehi\Artist\Console\Tests\Command;

use Sedehi\Artist\Console\Tests\SectionTestCase;

class MakeMigrationTest extends SectionTestCase
{
    /**
     * @return void
     * @test
     */
    public function make_migration_with_section_option()
    {
        $name = 'create_test_table';
        $this->artisan('make:migration', [
            'name'      => $name,
            '--section' => $this->sectionName,
        ]);
        $migration = glob(app_path('Http/Controllers/'.$this->sectionName.'/database/migrations/*_create_test_table.php'));
        $this->assertCount(1, $migration);
    }
}
