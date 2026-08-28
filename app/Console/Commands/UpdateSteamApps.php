<?php

declare(strict_types=1);

namespace Zeropingheroes\Lanager\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Zeropingheroes\Lanager\Models\SteamApp;
use Zeropingheroes\SteamApis\SteamWebApi\Requests\IStoreService\GetAppListRequest;
use Zeropingheroes\SteamApis\SteamWebApi\SteamWebApiConnector;

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

        $steamWebApiConnector = app(SteamWebApiConnector::class);

        $getAppListRequest = new GetAppListRequest;
        $appPaginator = $getAppListRequest->paginate($steamWebApiConnector);
        $appPaginator->setPerPageLimit(50000);

        if (! SteamApp::count()) {
            $this->import($appPaginator);
        } else {
            $this->update($appPaginator);
        }

        return 0;
    }

    /**
     * Import the apps
     */
    private function import($appPaginator): void
    {
        // Temporarily increase memory limit
        ini_set('memory_limit', '256M');

        $this->info(trans('phrase.database-empty-batch-import'));

        $databaseApps = [];

        foreach ($appPaginator as $response) {
            $apps = array_map(fn ($app) => [
                'id' => $app['appid'],
                'name' => $app['name'],
            ], $response->json('response.apps'));

            $databaseApps = array_merge($apps, $databaseApps);
        }

        $message = trans('phrase.importing-x-steam-apps', ['x' => count($databaseApps)]);
        $this->info($message);
        Log::info($message);

        // Chunk the apps into blocks of 500
        $chunkedApps = array_chunk($databaseApps, 500);

        $progressBar = $this->output->createProgressBar(count($chunkedApps));
        $progressBar->setFormat('%current%/%max% %bar% %percent%% - %estimated%');

        $importedCount = 0;

        // Insert the chunks
        foreach ($chunkedApps as $chunkedApp) {
            SteamApp::insert($chunkedApp);
            $importedCount += count($chunkedApp);
            $progressBar->advance();
        }

        $progressBar->finish();
        $message = trans('phrase.x-steam-apps-imported', ['x' => $importedCount]);
        $this->info(PHP_EOL.$message);
        Log::info($message);
    }

    /**
     * Execute the console command.
     */
    private function update($appPaginator): void
    {
        $apps = $appPaginator->collect();

        $message = trans('phrase.updating-x-steam-apps', ['x' => $apps->count()]);
        $this->info($message);
        Log::info($message);

        // Initialise counter and progress bar
        $progressBar = $this->output->createProgressBar($apps->count());
        $progressBar->setFormat('%current%/%max% %bar% %percent%% - %estimated%');

        $updatedCount = 0;

        foreach ($apps as $app) {
            $databaseApp = SteamApp::updateOrCreate(
                ['id' => $app->appid],
                ['name' => $app->name]
            );
            if ($databaseApp->wasChanged()) {
                $updatedCount++;
            }

            $progressBar->advance();
        }

        $progressBar->finish();

        $message = trans('phrase.x-steam-apps-updated', ['x' => $updatedCount]);
        $this->info(PHP_EOL.$message);
        Log::info($message);
    }
}
