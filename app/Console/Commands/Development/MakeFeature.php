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
     * @param $stubPath
     * @param $replacements
     * @param $outputPath
     */
    private function makeFileFromStub($stubPath, $replacements, $outputPath)
    {
        $item = studly_case(basename($stubPath, '.stub'));

        if (!file_exists($stubPath)) {
            $this->error(__('phrase.item-not-found', ['item' => $item . ' stub']));
            return;
        }

        if (file_exists($outputPath)) {
            $this->error(__('phrase.item-already-exists', ['item' => $item]));
            return;
        }

        $stub = file_get_contents($stubPath);

        foreach ($replacements as $find => $replace) {
            $stub = str_replace('{{' . $find . '}}', $replace, $stub);
        }
        if (file_put_contents($outputPath, $stub) !== false) {
            $this->info(__('phrase.item-created-successfully', ['item' => $item]) . '.');
        }

    }
}
