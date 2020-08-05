<?php

namespace Sedehi\Artist\Console\Command;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Sedehi\Artist\Console\Questions\SectionName;
use Sedehi\Artist\Console\Traits\Interactive;

class MakeArtistResource extends Command implements SectionName
{
    use Interactive;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:artist-resource
        {name : The name of the resource}
        {--section= : The name of the section}
        {--in : Interactive mode}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new artist resource';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->interactive();

        $resourcePath = app_path(Str::studly($this->argument('name')).'.php');

        if ($this->option('section')) {
            $resourcePath = app_path('Http/Controllers/'.Str::studly($this->option('section')).'/'.Str::studly($this->argument('name')).'.php');
        }

        if (File::exists($resourcePath)) {
            $this->error('Resource already exists.');

            return false;
        }

        if ($this->option('section')) {
            if (! File::isDirectory(app_path('Http/Controllers/'.Str::studly($this->option('section'))))) {
                File::makeDirectory(app_path('Http/Controllers/'.Str::studly($this->option('section'))), 0775, true);
            }
        }

        $data = File::get(__DIR__.'/stubs/artist-resource.stub');

        $namespace = str_replace('\\','',$this->laravel->getNamespace());
        $fullModelClass = $this->laravel->getNamespace().str_replace('Resource', '', Str::studly($this->argument('name')));

        if ($this->option('section')) {
            $namespace = $this->laravel->getNamespace().'Http\\Controllers\\'.Str::studly($this->option('section'));
            $fullModelClass = $this->laravel->getNamespace().'Http\\Controllers\\'.Str::studly($this->option('section')).'\\Models\\'.str_replace('Resource', '', Str::studly($this->argument('name')));
        }

        $data = str_replace('{{{DummyNamespace}}}',  $namespace, $data);
        $data = str_replace('{{{DummyFullModelClass}}}', $fullModelClass, $data);
        $data = str_replace('{{{DummyModel}}}', str_replace('Resource', '', Str::studly($this->argument('name'))), $data);
        $data = str_replace('{{{ClassName}}}', Str::studly($this->argument('name')), $data);

        File::put($resourcePath, $data);

        $this->info('Resource created successfully.');
    }
}
