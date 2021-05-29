<?php

namespace Sedehi\Artist\Console\Command;

use App\Http\Kernel;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

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

        $this->registerMigrations();
        $this->registerRoutes();


        $email = $this->ask('What`s the admin email ?', 'admin@example.com');
        $password = $this->secret('What`s the admin password ? [default: 12345678]');

        $this->updateConfigFiles();
        $this->registerAdminMiddleware();

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


    public function registerMigrations()
    {
        $appServiceProviderPath = app_path('Providers/AppServiceProvider.php');
        $appServiceProvider = file_get_contents($appServiceProviderPath);
        $eol = $this->EOL($appServiceProvider);
        if (!Str::contains($appServiceProvider, 'loadMigration')) {
            $lines = file($appServiceProviderPath);
            $appServiceProvider = '';
            $linePointer = null;
            foreach ($lines as $lineNumber => $line) {
                $appServiceProvider .= $line;
                if (Str::contains($line, 'boot()')) {
                    if (Str::contains($line, '{')) {
                        $appServiceProvider .= $eol;
                        $appServiceProvider .= $this->migrationsLoadCode();
                    } else {
                        $linePointer = $lineNumber + 1;
                    }
                }
                if ($linePointer === $lineNumber) {
                    $appServiceProvider .= $this->migrationsLoadCode();
                    $linePointer = null;
                }
            }
            $appServiceProvider = substr_replace($appServiceProvider, $eol.file_get_contents(__DIR__.'/stubs/serviceprovider-methods.stub'), strrpos($appServiceProvider, '}') - 1, 0);
            file_put_contents(app_path('Providers/AppServiceProvider.php'), $appServiceProvider);
        }
    }

    public function registerRoutes()
    {
        $routeServiceProviderPath = app_path('Providers/RouteServiceProvider.php');
        $routeServiceProvider = file_get_contents($routeServiceProviderPath);
        $eol = $this->EOL($routeServiceProvider);
        file_put_contents($routeServiceProviderPath, str_replace("->group(base_path('routes/api.php'));", $this->apiRouteCode(), $routeServiceProvider));
        $routeServiceProvider = file_get_contents($routeServiceProviderPath);
        file_put_contents($routeServiceProviderPath, str_replace("->group(base_path('routes/web.php'));", $this->webRouteCode(), $routeServiceProvider));
    }

    public function registerAdminRoutes()
    {
        if (!File::exists(base_path('routes/admin.php'))) {
            file_put_contents(base_path('routes/admin.php'), file_get_contents(__DIR__.'/stubs/admin-routes.stub'));
        }
        $routeServiceProviderPath = app_path('Providers/RouteServiceProvider.php');
        $routeServiceProvider = file_get_contents($routeServiceProviderPath);
        if (Str::contains($routeServiceProvider, 'mapAdminRoutes')) {
            return;
        }
        $eol = $this->EOL($routeServiceProvider);
        file_put_contents($routeServiceProviderPath, str_replace('$this->mapWebRoutes();', '$this->mapWebRoutes();'.$eol."\t\t".'$this->mapAdminRoutes();', $routeServiceProvider));
        $routeServiceProvider = file_get_contents($routeServiceProviderPath);
        if (!Str::contains(file_get_contents($routeServiceProviderPath), 'function mapAdminRoutes')) {
            $routeServiceProvider = substr_replace($routeServiceProvider, $eol.$this->adminRouteCode(), strrpos($routeServiceProvider, '}') - 1, 0);
            file_put_contents($routeServiceProviderPath, $routeServiceProvider);
        }
    }

    protected function migrationsLoadCode()
    {
        return '        if ($this->app->runningInConsole()) {
            $this->loadMigration();
        }'."\n";
    }

    protected function adminRouteCode()
    {
        return '    protected function mapAdminRoutes(){
        Route::namespace($this->namespace)->middleware(\'web\')->group(function(){
            require base_path(\'routes/admin.php\');
            Route::prefix(\'admin\')->middleware(\'admin\')->group(function(){
                $routes = glob(app_path(\'Http/Controllers/*/routes/admin.php\'));
                foreach($routes as $route) {
                    require $route;
                }
            });
        });
    }';
    }

    protected function apiRouteCode()
    {
        return '->group(function () {
            $routes = glob(app_path(\'Http/Controllers/*/routes/api.php\'));
            foreach ($routes as $route) {
                require $route;
            }
            require base_path(\'routes/api.php\');
        });';
    }

    protected function webRouteCode()
    {
        return '->group(function () {
            $routes = glob(app_path(\'Http/Controllers/*/routes/web.php\'));
            foreach ($routes as $route) {
                require $route;
            }
            require base_path(\'routes/web.php\');
        });';
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


    private function updateAppConfig()
    {
        $appConfigPath = config_path('app.php');
        $appConfig = file_get_contents($appConfigPath);
        $eol = $this->EOL($appConfig);
        if (!Str::contains($appConfig, 'Morilog\Jalali\Jalalian::class')) {
            $lines = file($appConfigPath);
            $appConfig = '';
            foreach ($lines as $lineNumber => $line) {
                $appConfig .= $line;
                if (Str::contains($line, '\'aliases\'')) {
                    $appConfig .= "\t\t".'\'Jalalian\' => Morilog\Jalali\Jalalian::class,';
                }
            }
            file_put_contents($appConfigPath, $appConfig);
            $this->alert('For use jalalian dates please run this command ');
            $this->output->title('composer require morilog/jalali:3.*');
            $this->output->newLine();
        }
    }


    private function updateConfigFiles()
    {
        $this->updateAppConfig();
        $this->updateViewConfig();
    }


    private function updateViewConfig()
    {
        $viewConfigPath = config_path('view.php');
        $viewConfig = file_get_contents($viewConfigPath);
        $eol = $this->EOL($viewConfig);
        if (!Str::contains($viewConfig, 'app_path(\'Http/Controllers\')')) {
            $lines = file($viewConfigPath);
            $viewConfig = '';
            foreach ($lines as $lineNumber => $line) {
                $viewConfig .= $line;
                if (Str::contains($line, 'resource_path(\'views\')')) {
                    $viewConfig .= "\t\t".'app_path(\'Http/Controllers\'),'.$eol;
                }
            }
            file_put_contents($viewConfigPath, $viewConfig);
        }
    }

    private function registerAdminMiddleware()
    {
        $middlewareGroups = app()->make(Kernel::class)->getMiddlewareGroups();
        $kernelPath = app_path('Http/Kernel.php');
        $kernel = file_get_contents($kernelPath);
        $eol = $this->EOL($kernel);

        if (!Arr::has($middlewareGroups, 'admin')) {
            $kernel = str_replace(
                "'web' => [".$eol,
                "'admin'  =>  [
            'auth:admin',
            \App\Http\Middleware\DefineGates::class,
            \App\Http\Middleware\Permission::class,
        ],".$eol."\t\t'web' => [".$eol,
                $kernel
            );

            file_put_contents($kernelPath, $kernel);
        }
    }

    private function publishUserSection()
    {
        $this->call('vendor:publish', ['--tag' =>  'section-user-directory']);

        // create user section language file
        if (!File::exists(resource_path('lang/fa/user.php'))) {
            if (!File::isDirectory(resource_path('lang/fa'))) {
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
        $this->call('section:define-gates-middleware', ['name' => 'DefineGates']);
        $this->call('section:permission-middleware', ['name' => 'Permission']);

        return $this->call('vendor:publish', ['--tag' =>  'section-role-directory']);
    }


}
