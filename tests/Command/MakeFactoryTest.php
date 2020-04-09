<?php

namespace Sedehi\Artist\Console\Tests\Command;

use Illuminate\Support\Facades\Artisan;
use Sedehi\Artist\Console\Tests\SectionTestCase;

class MakeFactoryTest extends  SectionTestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testSectionFactory()
    {
        $section = 'Test';
        $name = 'TestFactory';
        $path = app_path('Http/Controllers/'.$section."/database/factories/{$name}.php");

        $this->artisan('make:factory', [
            'name'      => $name,
            '--section' => $section
        ]);

        $this->assertFileExists($path);
    }
}
