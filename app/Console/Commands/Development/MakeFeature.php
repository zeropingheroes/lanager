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
        $this->description = __('phrase.create-classes-for-feature');

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
        $this->call(
            'make:policy',
            [
                'name' => $name . 'Policy',
                '--model' => $name,
                '--no-interaction' => true,
            ]
        );
    }
}
