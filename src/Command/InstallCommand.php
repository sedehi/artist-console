<?php

namespace Sedehi\Artist\Console\Command;

use App\Http\Controllers\Role\database\seeds\RoleTableSeeder;
use App\Http\Controllers\Role\Models\Role;
use App\Http\Controllers\User\Models\Admin;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
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

        $this->comment('Publishing Artist View...');
        $this->callSilent('vendor:publish', ['--tag' => 'artist-views']);
        $this->callSilent('vendor:publish', ['--tag' => 'laravel-pagination']);


        if (! File::exists(base_path('routes/artist.php'))) {
            File::put(base_path('routes/artist.php'), '<?php ');
        }

        $this->updateAuthConfig();

        if(!File::isDirectory(app_path('Http/Controllers/Role'))){
            $this->publishRoleSection();
        }
        if(!File::isDirectory(app_path('Http/Controllers/User'))) {
            $this->publishUserSection();
        }

        $email = $this->ask('What`s the admin email ?', 'admin@example.com');
        $password = $this->secret('What`s the admin password ? [default: 12345678]');

        $this->call('migrate', [
            '--path' => [
                'database/migrations',
                'app/Http/Controllers/User/database/migrations',
                'app/Http/Controllers/Role/database/migrations',
            ],
        ]);

        $admin = Admin::where('email', $email)->first();

        if (is_null($admin)) {
            $admin = Admin::create([
                'email'    => $email,
                'password' => bcrypt($password ?? '12345678'),
            ]);
            $this->call('db:seed', [
                '--class' => RoleTableSeeder::class,
            ]);
            $admin->roles()->attach(Role::first());

            $this->info('Admin account created successfully.');
        }

        $this->info('Artist scaffolding installed successfully.');
    }

    private function publishUserSection()
    {
        $this->call('vendor:publish', ['--tag' =>  'section-user-directory']);

        $files = $this->rglob(app_path('Http/Controllers/User/*.stub'));
        foreach ($files as $file) {
            File::move($file, str_replace('.stub', '.php', $file));
        }

        // create user section language file
        if (! File::exists(resource_path('lang/fa/user.php'))) {
            if (! File::isDirectory(resource_path('lang/fa'))) {
                File::makeDirectory(resource_path('lang/fa'), 0755, true, true);
            }
            File::put(
                resource_path('lang/fa/user.php'),
                file_get_contents(__DIR__.'/stubs/langs/user.stub')
            );
        }
    }

    private function publishRoleSection()
    {
        $this->call('vendor:publish', ['--tag' =>  'section-role-directory']);
        $files = $this->rglob(app_path('Http/Controllers/Role/*.stub'));
        foreach ($files as $file) {
            File::move($file, str_replace('.stub', '.php', $file));
        }
    }

    private function rglob($pattern, $flags = 0)
    {
        $files = glob($pattern, $flags);
        foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {
            $files = array_merge($files, $this->rglob($dir.'/'.basename($pattern), $flags));
        }

        return $files;
    }


    private function updateAuthConfig()
    {
        $authConfigData = config('auth');
        $authConfigPath = config_path('auth.php');
        $authConfig = file_get_contents($authConfigPath);
        $eol = $this->EOL($authConfig);

        if (!Arr::has($authConfigData, 'guards.admin')) {
            $authConfig = str_replace(
                "'guards' => [".$eol,
                "'guards' => [".$eol."\t\t'artist' => [
            'driver' => 'session',
            'provider' => 'artist',
        ],".$eol,
                $authConfig
            );

            file_put_contents($authConfigPath, $authConfig);
        }

        if (!Arr::has($authConfigData, 'providers.admins')) {
            file_put_contents($authConfigPath, str_replace(
                "'providers' => [".$eol,
                "'providers' => [".$eol."\t\t'artist' => [
            'driver' => 'eloquent',
            'model' => \App\Http\Controllers\User\Models\Admin::class,
        ],".$eol,
                $authConfig
            ));
        }
    }

    protected function EOL(string $routeServiceProvider)
    {
        $lineEndingCount = [
            "\r\n" => substr_count($routeServiceProvider, "\r\n"),
            "\r"   => substr_count($routeServiceProvider, "\r"),
            "\n"   => substr_count($routeServiceProvider, "\n"),
        ];

        return array_keys($lineEndingCount, max($lineEndingCount))[0];
    }
}
