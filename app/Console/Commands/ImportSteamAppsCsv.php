<?php

namespace Zeropingheroes\Lanager\Console\Commands;

use Illuminate\Console\Command;
use League\Csv\Exception;
use League\Csv\Reader;
use Zeropingheroes\Lanager\SteamApp;

class ImportSteamAppsCsv extends Command
{
    private static $filename = 'steam_apps.csv';

    /**
     * Set command signature and description.
     */
    public function __construct()
    {
        $this->signature = 'lanager:import-steam-apps-csv';
        $this->description = trans('phrase.import-steam-apps-csv');

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $reader = Reader::createFromPath($this::$filename, 'r');
        } catch (Exception $e) {
            $this->info(trans('phrase.csv-not-found-aborting'));

            return 1;
        }
        $csvApps = $reader->getRecords(['id', 'name', 'type']);

        if (! SteamApp::count()) {
            $this->import($csvApps);
        } else {
            $this->update($csvApps, count($reader));
        }

        return 0;
    }

    /**
     * @param $csvApps
     */
    private function import($csvApps): void
    {
        $this->info(trans('phrase.database-empty-batch-import'));

        $arrayApps = [];

        // Convert CSV object to array
        foreach ($csvApps as $csvApp) {
            $arrayApps[] = [
                'id' => $csvApp['id'],
                'name' => $csvApp['name'],
                'type' => $csvApp['type'],
            ];
        }

        // Chunk the array into blocks of 500 apps
        $chunkedApps = array_chunk($arrayApps, 500);

        $progress = $this->output->createProgressBar(count($chunkedApps));
        $progress->setFormat('%bar% %percent%% - %estimated%');
        $importedCount = 0;

        // Insert the chunks
        foreach ($chunkedApps as $chunk) {
            SteamApp::insert($chunk);
            $importedCount = $importedCount + count($chunk);
            $progress->advance();
        }
        $progress->finish();

        $this->info(PHP_EOL.trans('phrase.x-steam-apps-imported', ['x' => $importedCount]));
    }

    /**
     * @param int $csvCount
     * @param $csvApps
     */
    private function update($csvApps, int $csvCount): void
    {
        $this->info(trans('phrase.updating-x-steam-apps', ['x' => SteamApp::count()]));

        // Initialise progress bar and counter
        $progress = $this->output->createProgressBar($csvCount);
        $progress->setFormat('%current%/%max% %bar% %percent%% - %estimated%');
        $updatedCount = 0;

        foreach ($csvApps as $csvApp) {
            $databaseApp = SteamApp::updateOrCreate(
                [
                    'id' => $csvApp['id'],
                ],
                [
                    'name' => $csvApp['name'],
                    'type' => $csvApp['type'],
                ]
            );
            if ($databaseApp->wasChanged()) {
                $updatedCount++;
            }
            $progress->advance();
        }
        $progress->finish();
        $this->info(PHP_EOL.trans('phrase.x-steam-apps-updated', ['x' => $updatedCount]));
    }
}
