<?php

namespace Zeropingheroes\Lanager\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use League\Csv\Writer;
use Zeropingheroes\Lanager\Models\SteamApp;

class ExportSteamAppsCsv extends Command
{
    private static $filename = 'steam_apps.csv';
    private static $chunkSize = 10000;

    /**
     * Set command signature and description.
     */
    public function __construct()
    {
        $this->signature = 'lanager:export-steam-apps-csv '
            . '{--yes : ' . trans('phrase.suppress-confirmations') . '}';
        $this->description = trans('phrase.export-steam-apps-csv');

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
            $this->error(trans('phrase.database-empty-aborting'));

            return 1;
        }

        if (file_exists(Storage::path($this::$filename))) {
            if (!$this->option('yes') && !$this->confirm(trans('phrase.overwrite-existing-csv'))) {
                return 1;
            }
        }
        $totalApps = SteamApp::count();

        $this->info(trans('phrase.exporting-x-steam-apps-to-csv', ['x' => $totalApps]));

        $progress = $this->output->createProgressBar(floor($totalApps / $this::$chunkSize));
        $progress->setFormat('%bar% %percent%% - %estimated%');

        $csv = Writer::createFromPath(Storage::path($this::$filename), 'w+');

        SteamApp::chunk($this::$chunkSize, function ($apps) use ($progress, $csv) {
            $csv->insertAll($apps->toArray());
            $progress->advance();
        });

        $progress->finish();

        $this->info(PHP_EOL . trans('phrase.x-steam-apps-exported', ['x' => $totalApps]));

        return 0;
    }
}
