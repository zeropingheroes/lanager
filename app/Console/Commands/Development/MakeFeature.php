<?php

namespace Zeropingheroes\Lanager\Console\Commands\Development;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeFeature extends Command
{
    /*
     * Replacements to be made
     */
    private $replacements;

    /**
     * Set command signature and description
     */
    public function __construct()
    {
        $this->signature = 'make:feature {name : ' . __('phrase.name-of-feature') . '}';
        $this->description = __('phrase.create-files-for-feature');

        parent::__construct();

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = Str::studly(Str::singular($this->argument('name')));

        $this->replacements = [
            'model' => $name,
            'variable' => Str::camel($name),
            'variables' => Str::camel(Str::plural($name, 2)),
            'route' => Str::kebab(Str::plural($name, 2)),
            'view' => Str::kebab(Str::plural($name, 2)),
            'lang' => Str::kebab($name),
            'langs' => Str::kebab(Str::plural($name, 2)),
            'table' => Str::snake(Str::plural($name, 2)),
        ];

        $this->makeModel();
        $this->makeRoute();
        $this->makeController();
        $this->makePolicy();
        $this->makeStoreRequest();
        $this->makeViews();
        $this->makeBreadcrumbs();
        $this->makeMigration();
        return 0;
    }

    private function makeController()
    {
        $stubPath = __DIR__ . '/stubs/controller.stub';
        $outputPath = app_path("Http/Controllers/{$this->replacements['model']}Controller.php");

        $this->makeFileFromStub($stubPath, $outputPath);
    }

    private function makeMigration()
    {
        $this->call(
            'make:migration',
            [
                'name' => $this->replacements['table'] . '_table_create',
                '--table' => $this->replacements['table'],
                '--no-interaction' => true,
            ]
        );
    }

    private function makePolicy()
    {
        $stubPath = __DIR__ . '/stubs/policy.stub';
        $outputPath = app_path("Policies/{$this->replacements['model']}Policy.php");

        $this->makeFileFromStub($stubPath, $outputPath);
    }

    private function makeStoreRequest()
    {
        $stubPath = __DIR__ . '/stubs/request.stub';
        $outputPath = app_path("Requests/Store{$this->replacements['model']}Request.php");

        $this->makeFileFromStub($stubPath, $outputPath);
    }

    private function makeViews()
    {
        $viewStubs = ['create.stub', 'edit.stub', 'index.stub', 'show.stub', 'partials/actions-dropdown.stub', 'partials/form.stub', 'partials/list.stub'];

        $viewPath = resource_path("views/pages/{$this->replacements['view']}/");

        foreach ($viewStubs as $viewStub) {
            $outputPath = $viewPath . str_replace('.stub', '.blade.php', $viewStub);
            if (!is_dir(dirname($outputPath))) {
                mkdir(dirname($outputPath), 755, true);
            }
            $label = 'View "' . Str::studly(basename($viewStub, '.stub')) . '"';
            $this->makeFileFromStub(__DIR__ . '/stubs/views/' . $viewStub, $outputPath, $label);
        }
    }

    /**
     * @param $stubPath
     * @param $outputPath
     * @param string $label
     */
    private function makeFileFromStub($stubPath, $outputPath, $label = null, $append = false)
    {
        $label = $label ?? Str::studly(basename($stubPath, '.stub'));

        if (!file_exists($stubPath)) {
            $this->error(__('phrase.item-not-found', ['item' => $label . ' stub']));
            return;
        }

        if (! $append && file_exists($outputPath)) {
            $this->error(__('phrase.item-already-exists', ['item' => $label]));
            return;
        }

        $stub = file_get_contents($stubPath);

        foreach ($this->replacements as $find => $replace) {
            $stub = str_replace('{{' . $find . '}}', $replace, $stub);
        }
        if (file_put_contents($outputPath, $stub, FILE_APPEND) !== false) {
            $this->info(__('phrase.item-created-successfully', ['item' => $label]) . '.');
        }
    }

    private function makeRoute()
    {
        $stubPath = __DIR__ . '/stubs/route.stub';
        $outputPath = base_path('routes/web.php');

        $this->makeFileFromStub($stubPath, $outputPath, null, true);
    }

    private function makeBreadcrumbs()
    {
        $stubPath = __DIR__ . '/stubs/breadcrumbs.stub';
        $outputPath = base_path('routes/breadcrumbs.php');

        $this->makeFileFromStub($stubPath, $outputPath, null, true);
    }

    private function makeModel()
    {
        $this->call(
            'make:model',
            [
                'name' => $this->replacements['model'],
                '--no-interaction' => true,
            ]
        );
    }
}
