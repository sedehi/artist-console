<?php

namespace Sedehi\Artist\Console\Command;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

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

        $email = $this->ask('What`s the admin email ?', 'admin@example.com');
        $password = $this->secret('What`s the admin password ? [default: 12345678]');

        $this->publishRoleSection();
        $this->publishUserSection();

        $this->call('migrate', [
            '--path' => [
                'database/migrations',
                'app/Http/Controllers/User/database/migrations',
                'app/Http/Controllers/Role/database/migrations',
            ],
        ]);

        $this->info('Artist scaffolding installed successfully.');
    }

    private function publishUserSection()
    {
        $this->call('vendor:publish', ['--tag' =>  'section-user-directory']);

        // create user section language file
        if (! File::exists(resource_path('lang/fa/user.php'))) {
            if (! File::isDirectory(resource_path('lang/fa'))) {
                File::makeDirectory(resource_path('lang/fa'), 0755, true, true);
            }
            file_put_contents(
                resource_path('lang/fa/user.php'),
                file_get_contents(__DIR__.'/stubs/user-lang.stub')
            );
        }
    }

    private function publishRoleSection()
    {
        return $this->call('vendor:publish', ['--tag' =>  'section-role-directory']);
    }
}
