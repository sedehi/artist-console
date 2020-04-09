<?php
namespace Sedehi\Artist\Console\Tests;


use Orchestra\Testbench\TestCase;
use Sedehi\Artist\Console\ArtistConsoleServiceProvider;

class SectionTestCase extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->app->register(ArtistConsoleServiceProvider::class);

    }
}
