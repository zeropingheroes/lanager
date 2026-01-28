<?php

namespace Zeropingheroes\Lanager\Console\Commands;

use Astrotomic\SteamSdk\SteamConnector;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Zeropingheroes\Lanager\Models\SteamApp;

class UpdateSteamApps extends Command
{
    /**
     * Set command signature and description.
     */
    public function __construct()
    {
        ini_set('memory_limit', '256M');

        $this->signature = 'lanager:update-steam-apps';
        $this->description = trans('phrase.update-steam-apps');

        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info(trans('phrase.requesting-list-of-all-apps-from-steam-api'));

        $steamConnector = app(SteamConnector::class);

        $maxResults = 50000;
        $apps = new Collection();
        $appList = $steamConnector->GetAppList($maxResults, null, true, false, true);

        while($appList->have_more_results) {
            $appList = $steamConnector->GetAppList($maxResults, $appList->last_appid);
            $apps = $apps->concat($appList->apps);
        }

        if (!SteamApp::count()) {
            $this->import($apps);
        } else {
            $this->update($apps);
        }

        return 0;
    }

    /**
     * Import the apps
     */
    private function import($apps): void
    {
        // Temporarily increase memory limit
        ini_set('memory_limit', '256M');

        $this->info(trans('phrase.database-empty-batch-import'));

        $databaseApp = [];

        // Create an array ready for batch inserting
        foreach ($apps as $app) {
            $databaseApp[] = ['id' => $app->appid, 'name' => $app->name];
        }

        $message = trans('phrase.importing-x-steam-apps', ['x' => count($apps)]);
        $this->info($message);
        Log::info($message);

        // Chunk the apps into blocks of 500
        $chunkedApps = array_chunk($databaseApp, 500);

        $progress = $this->output->createProgressBar(count($chunkedApps));
        $progress->setFormat('%current%/%max% %bar% %percent%% - %estimated%');
        $importedCount = 0;

        // Insert the chunks
        foreach ($chunkedApps as $chunk) {
            SteamApp::insert($chunk);
            $importedCount = $importedCount + count($chunk);
            $progress->advance();
        }
        $progress->finish();
        $message = trans('phrase.x-steam-apps-imported', ['x' => $importedCount]);
        $this->info(PHP_EOL . $message);
        Log::info($message);
    }

    /**
     * Execute the console command.
     */
    private function update($apps): void
    {
        $message = trans('phrase.updating-x-steam-apps', ['x' => SteamApp::count()]);
        $this->info($message);
        Log::info($message);

        // Initialise counter and progress bar
        $progress = $this->output->createProgressBar(count($apps));
        $progress->setFormat('%current%/%max% %bar% %percent%% - %estimated%');
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

        $message = trans('phrase.x-steam-apps-updated', ['x' => $updatedCount]);
        $this->info(PHP_EOL . $message);
        Log::info($message);
    }
}
