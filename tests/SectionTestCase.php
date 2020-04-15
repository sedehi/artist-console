<?php

namespace Sedehi\Artist\Console\Tests;

use Illuminate\Support\Facades\File;
use Orchestra\Testbench\TestCase;
use Sedehi\Artist\Console\ArtistConsoleServiceProvider;

abstract class SectionTestCase extends TestCase
{
    public $sectionName = 'TestSection';
    public $factoryName = 'TestFactory';
    public $eventName = 'TestEvent';
    public $listenerName = 'TestListener';
    public $modelName = 'TestModel';
    public $channelName = 'TestChannel';

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
