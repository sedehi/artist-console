<?php

namespace Sedehi\Artist\Console\Tests;

use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\File;
use Sedehi\Artist\Console\ArtistConsoleServiceProvider;

abstract class SectionTestCase extends TestCase
{
    public $sectionName = 'TestSection';
    public $factoryName = 'TestFactory';
    public $modelName = 'TestModel';

    public function setUp(): void
    {
        parent::setUp();

        $this->app->register(ArtistConsoleServiceProvider::class);
    }

    public function tearDown(): void
    {
        File::deleteDirectory(app_path('Http/Controllers/'.$this->sectionName));
    }
}
