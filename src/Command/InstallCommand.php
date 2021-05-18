<?php

namespace Sedehi\Artist\Console\Command;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'artist:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install all of the Section resources';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->comment('Publishing Artist Assets...');
        $this->callSilent('vendor:publish', ['--tag' => 'artist-assets']);

        $this->comment('Publishing Artist Configuration...');
        $this->callSilent('vendor:publish', ['--tag' => 'artist-config']);

        $this->comment('Publishing Artist Translation...');
        $this->callSilent('vendor:publish', ['--tag' => 'artist-lang']);

        $this->info('Artist scaffolding installed successfully.');
    }
}
