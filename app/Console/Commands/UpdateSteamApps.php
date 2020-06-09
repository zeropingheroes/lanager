<?php

namespace Zeropingheroes\Lanager\Console\Commands;

use Illuminate\Console\Command;
use Log;
use Syntax\SteamApi\Facades\SteamApi;
use Zeropingheroes\Lanager\SteamApp;

class UpdateSteamApps extends Command
{
    /**
     * Set command signature and description.
     */
    public function __construct()
    {
        $this->signature = 'lanager:update-steam-apps';
        $this->description = trans('phrase.update-steam-apps');

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info(trans('phrase.requesting-list-of-all-apps-from-steam-api'));
        $apps = SteamApi::app()->GetAppList();

        if (! SteamApp::count()) {
            $this->import($apps);
        } else {
            $this->update($apps);
        }

        return 0;
    }

    /**
     * @param $apps
     * @return void
     */
    private function import($apps): void
    {
        $this->info(trans('phrase.database-empty-batch-import'));

        // Create an array ready for batch inserting
        foreach ($apps as $key => $app) {
            $apps[$key] = ['id' => $app->appid, 'name' => $app->name];
        }

        $message = trans('phrase.importing-x-steam-apps', ['x' => count($apps)]);
        $this->info($message);
        Log::info($message);

        // Chunk the apps into blocks of 500
        $chunkedApps = array_chunk($apps, 500);

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
        $this->info(PHP_EOL.$message);
        Log::info($message);
    }

    /**
     * @param $apps
     * @return void
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
        $this->info(PHP_EOL.$message);
        Log::info($message);
    }
}
