<?php

namespace Zeropingheroes\Lanager\Console\Commands;

use Illuminate\Console\Command;
use Syntax\SteamApi\Facades\SteamApi as Steam;
use Zeropingheroes\Lanager\SteamApp;

class UpdateSteamApps extends Command
{
    /**
     * Set command signature and description
     */
    public function __construct()
    {
        $this->signature = 'lanager:update-steam-apps';
        $this->description = __('phrase.update-steam-apps');

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info(__('phrase.requesting-list-of-all-apps-from-steam-api'));
        $apps = Steam::app()->GetAppList();

        $existingCount = SteamApp::count();

        if (!$existingCount) {
            $this->import($apps);
        } else {
            $this->update($apps);
        }
    }

    /**
     * @param $apps
     * @param int $fromSteamCount
     * @return array
     */
    private function import($apps): void
    {
        $this->info(__('phrase.database-empty-batch-import'));

        // Create an array ready for batch inserting
        foreach ($apps as $key => $app) {
            $apps[$key] = ['id' => $app->appid, 'name' => $app->name];
        }

        $this->info(__('phrase.importing-x-steam-apps', ['x' => count($apps)]));

        // Chunk the apps into blocks of 500
        $chunkedApps = array_chunk($apps, 500);

        $progress = $this->output->createProgressBar(count($chunkedApps));
        $progress->setFormat("%current%/%max% %bar% %percent%%");
        $importedCount = 0;

        // Insert the chunks
        foreach ($chunkedApps as $chunk) {
            SteamApp::insert($chunk);
            $importedCount = $importedCount + count($chunk);
            $progress->advance();
        }
        $progress->finish();
        $this->info(PHP_EOL . __('phrase.x-steam-apps-imported', ['x' => $importedCount]));
    }

    /**
     * @param $fromSteamCount
     * @param $existingCount
     * @param $apps
     */
    private function update($apps): void
    {
        $this->info(__('phrase.updating-x-steam-apps', ['x' => SteamApp::count()]));

        // Initialise counter and progress bar
        $progress = $this->output->createProgressBar(count($apps));
        $progress->setFormat("%current%/%max% %bar% %percent%%");
        $updatedCount = 0;

        foreach ($apps as $app) {
            $databaseApp = SteamApp::updateOrCreate(
                ['id' => $app->appid],
                ['name' => $app->name]
            );
            if ($databaseApp->wasChanged()) {
                $updatedCount++;
            }
            $progress->advance();
        }
        $progress->finish();

        $this->info(PHP_EOL . __('phrase.x-steam-apps-updated', ['x' => $updatedCount]));
    }
}
