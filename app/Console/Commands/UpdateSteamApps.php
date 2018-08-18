<?php

namespace Zeropingheroes\Lanager\Console\Commands;

use Illuminate\Console\Command;
use Steam;
use Zeropingheroes\Lanager\SteamApp;

class UpdateSteamApps extends Command
{
    /**
     * Set command signature and description
     */
    public function __construct()
    {
        $this->signature = 'lanager:update-steam-apps';
        $this->description = __('phrase.update-database-with-apps-from-steam');

        parent::__construct();
    }
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info(__('phrase.requesting-details-of-all-apps-from-steam'));
        $apps = Steam::app()->GetAppList();

        $fromSteamCount = count($apps);
        $existingCount = SteamApp::count();

        // If there are apps in the database
        // add any new and update existing apps
        if ($existingCount) {

            $newCount = $fromSteamCount - $existingCount;

            // Otherwise check and update/insert each app individually (slower but safer)
            $this->info(__('phrase.updating-x-steam-apps-already-in-db-and-adding-y-new', ['x' => $existingCount, 'y' => $newCount]));

            // Initialise counter and progress bar
            $changedCount = 0;
            $progress = $this->output->createProgressBar($fromSteamCount);
            $progress->setFormat("%bar% %percent%%");

            foreach ($apps as $app) {
                $databaseApp = SteamApp::updateOrCreate(
                    ['id' => $app->appid],
                    ['name' => $app->name]
                );
                if ($databaseApp->wasChanged()) {
                    $changedCount++;
                }
                $progress->advance();
            }
            $progress->finish();

            $this->info(PHP_EOL . __('phrase.steam-app-update-complete-x-updates-y-new', ['x' => $changedCount, 'y' => $newCount]));
        } else {
            // Database is empty - prepare for bulk insert
            foreach ($apps as $key => $app) {
                $apps[$key] = ['id' => $app->appid, 'name' => $app->name];
            }

            $this->info(__('phrase.adding-x-steam-apps-to-db', ['x' => $fromSteamCount]));

            // Chunk the apps into blocks of 500
            $chunkedApps = array_chunk($apps, 500);

            $progress = $this->output->createProgressBar(count($chunkedApps));
            $progress->setFormat("%bar% %percent%%");

            // Insert the apps 500 at a time
            foreach ($chunkedApps as $apps) {
                SteamApp::insert($apps);
                $progress->advance();
            }
            $progress->finish();
            $this->info(PHP_EOL . __('phrase.steam-app-update-complete-x-added', ['x' => $fromSteamCount]));
        }
        return;
    }
}
