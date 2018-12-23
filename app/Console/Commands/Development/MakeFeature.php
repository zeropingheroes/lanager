<?php

namespace Zeropingheroes\Lanager\Console\Commands\Development;

use Illuminate\Console\Command;

class MakeFeature extends Command
{
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
        $name = $this->argument('name');

        $this->makeController($name);
        $this->makePolicy($name);
        $this->makeStoreRequest($name);
        $this->makeViews($name);
    }

    /**
     * @param $name
     */
    private function makeController($name)
    {
        $this->call(
            'make:controller',
            [
                'name' => $name . 'Controller',
                '--model' => $name,
                '--resource' => true,
                '--no-interaction' => true,
            ]
        );
    }

    /**
     * @param $name
     */
    private function makePolicy($name)
    {
        $replacements = [
            'ModelClassName' => studly_case($name),
            'ModelClassNameCamelCase' => camel_case($name)
        ];
        $stubPath = __DIR__ . '/stubs/policy.stub';
        $outputPath = app_path("Policies/{$name}Policy.php");

        $this->makeFileFromStub($stubPath, $replacements, $outputPath);
    }

    /**
     * @param $name
     */
    private function makeStoreRequest($name)
    {
        $replacements = [
            'ModelClassName' => studly_case($name),
        ];
        $stubPath = __DIR__ . '/stubs/request.stub';
        $outputPath = app_path("Requests/Store{$name}Request.php");

        $this->makeFileFromStub($stubPath, $replacements, $outputPath);
    }

    /**
     * @param $name
     */
    private function makeViews($name)
    {
        $viewStubs = ['create.stub', 'edit.stub', 'index.stub', 'show.stub', 'partials/form.stub', 'partials/list.stub'];

        $replacements = [
            'ModelClassName' => studly_case($name),
            'ModelClassNameCamelCase' => camel_case($name),
            'ModelClassNameKebabCase' => kebab_case($name),
        ];

        $viewPath = resource_path('views/pages/' . camel_case($name) . 's/');

        foreach ($viewStubs as $viewStub) {
            $outputPath = $viewPath . str_replace('.stub', '.blade.php', $viewStub);
            if (!is_dir(dirname($outputPath))) {
                mkdir(dirname($outputPath), 755, true);
            }
            $label = 'View "' . studly_case(basename($viewStub, '.stub')) . '"';
            $this->makeFileFromStub(__DIR__ . '/stubs/views/' . $viewStub, $replacements, $outputPath, $label);
        }
    }

    /**
     * @param $stubPath
     * @param $replacements
     * @param $outputPath
     * @param string $label
     */
    private function makeFileFromStub($stubPath, $replacements, $outputPath, $label = null)
    {
        $label = $label ?? studly_case(basename($stubPath, '.stub'));

        if (!file_exists($stubPath)) {
            $this->error(__('phrase.item-not-found', ['item' => $label . ' stub']));
            return;
        }

        if (file_exists($outputPath)) {
            $this->error(__('phrase.item-already-exists', ['item' => $label]));
            return;
        }

        $stub = file_get_contents($stubPath);

        foreach ($replacements as $find => $replace) {
            $stub = str_replace('{{' . $find . '}}', $replace, $stub);
        }
        if (file_put_contents($outputPath, $stub) !== false) {
            $this->info(__('phrase.item-created-successfully', ['item' => $label]) . '.');
        }

    }
}
