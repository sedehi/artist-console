<?php

namespace Sedehi\Artist\Console\Command;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeArtistResource extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:artist-resource
        {section : The name of the section}
        {name : The name of the resource}';

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
        $resourcePath = app_path('Http/Controllers/' . Str::studly($this->argument('section')) . '/' . Str::studly($this->argument('name')) . '.php');

        if (File::exists($resourcePath)) {
            $this->error('Resource already exists.');
            return false;
        }

        if (!File::isDirectory(app_path('Http/Controllers/' . Str::studly($this->argument('section'))))) {
            File::makeDirectory(app_path('Http/Controllers/' . Str::studly($this->argument('section'))), 0775, true);
        }

        $data = File::get(__DIR__ . '/stubs/artist-resource.stub');
        $data = str_replace(
            '{{{DummyNamespace}}}',
            $this->laravel->getNamespace() . 'Http\\Controllers\\' . Str::studly($this->argument('section')),
            $data
        );
        $data = str_replace(
            '{{{DummyFullModelClass}}}',
            $this->laravel->getNamespace() . 'Http\\Controllers\\' . Str::studly($this->argument('section')) . '\\Models\\' . str_replace('Resource', '', Str::studly($this->argument('name'))),
            $data
        );
        $data = str_replace(
            '{{{ClassName}}}',
            Str::studly($this->argument('name')),
            $data
        );
        $data = str_replace(
            '{{{DummyModel}}}',
            str_replace('Resource', '', Str::studly($this->argument('name'))),
            $data
        );

        File::put($resourcePath, $data);

        $this->info('Resource created successfully.');
    }
}
