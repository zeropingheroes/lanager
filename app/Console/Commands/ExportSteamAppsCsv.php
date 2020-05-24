<?php

namespace Zeropingheroes\Lanager\Console\Commands;

use Illuminate\Console\Command;
use League\Csv\Exception;
use Zeropingheroes\Lanager\SteamApp;
use League\Csv\Writer;

class ExportSteamAppsCsv extends Command
{

    private static $filename = 'steam_apps.csv';

    /**
     * Set command signature and description
     */
    public function __construct()
    {
        $this->signature = 'lanager:export-steam-apps-csv '
                         . '{--yes : ' . __('phrase.suppress-confirmations') . '}';
        $this->description = __('phrase.export-steam-apps-csv');

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (!SteamApp::count()) {
            $this->error(__('phrase.database-empty-aborting'));
            return;
        }

        if (file_exists($this::$filename)) {
            if (!$this->option('yes') && !$this->confirm(__('phrase.overwrite-existing-csv'))) {
                return;
            }
        }
        $steamApps = SteamApp::all()->toArray();
        $this->info(__('phrase.exporting-x-steam-apps-to-csv', ['x' => count($steamApps)]));
        $csv = Writer::createFromPath('steam_apps.csv', 'w+');
        $csv->insertAll($steamApps);
        $this->info(__('phrase.x-steam-apps-exported', ['x' => count($steamApps)]));
        return 0;
    }
}
