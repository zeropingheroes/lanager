<?php

namespace Zeropingheroes\Lanager\Console\Commands;

use Illuminate\Console\Command;
use Syntax\SteamApi\Exceptions\ApiCallFailedException;
use Syntax\SteamApi\Facades\SteamApi as Steam;
use Zeropingheroes\Lanager\SteamApp;
use League\Csv\Writer;
use League\Csv\Reader;
use bandwidthThrottle\tokenBucket\Rate;
use bandwidthThrottle\tokenBucket\TokenBucket;
use bandwidthThrottle\tokenBucket\BlockingConsumer;
use bandwidthThrottle\tokenBucket\storage\FileStorage;

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
        $this->importCsv();
        $this->updateAppList();
        $this->updateAppTypes();
        $this->exportCsv();
        return;
    }

    private function importCsv()
    {
        try {
            $reader = Reader::createFromPath('steam_apps.csv', 'r');
        } catch( \League\Csv\Exception $e) {
            $this->info(__('phrase.csv-file-not-found-skipping-import'));
            return;
        }
        $apps = $reader->getRecords(['id', 'name', 'type']);

        $existingCount = SteamApp::count();

        // If there are apps in the database, update instead of insert
        if ($existingCount) {

            $this->info(__('phrase.updating-x-existing-steam-apps-from-csv', ['x' => $existingCount]));

            // Initialise progress bar and counter
            $progress = $this->output->createProgressBar(count($reader));
            $progress->setFormat("%current%/%max% %bar% %percent%%");
            $changedCount = 0;

            foreach ($apps as $app) {
                $databaseApp = SteamApp::updateOrCreate(
                    [
                        'id' => $app['id']
                    ],
                    [
                        'name' => $app['name'],
                        'type' => $app['type'],
                    ]
                );
                if ($databaseApp->wasChanged()) {
                    $changedCount++;
                }
                $progress->advance();
            }
            $progress->finish();
            $this->info(PHP_EOL . __('phrase.x-steam-apps-updated-from-csv', ['x' => $changedCount]));
            return;
        }

        $this->info(__('phrase.importing-x-steam-apps-from-csv', ['x' => count($reader)]));

        // Convert CSV object to array
        foreach($apps as $app) {
            $arrayApps[] = [
                'id' => $app['id'],
                'name' => $app['name'],
                'type' => $app['type'],
            ];
        }

        // Chunk the apps into blocks of 500
        $chunkedApps = array_chunk($arrayApps, 500);

        $progress = $this->output->createProgressBar(count($chunkedApps));
        $progress->setFormat("%current%/%max% %bar% %percent%%");
        $importedCount = 0;

        // Insert the apps 500 at a time
        foreach ($chunkedApps as $chunk) {
            SteamApp::insert($chunk);
            $importedCount = $importedCount + count($chunk);
            $progress->advance();
        }
        $progress->finish();

        $this->info(PHP_EOL . __('phrase.x-steam-apps-imported-from-csv', ['x' => $importedCount]));
    }

    private function updateAppList()
    {
        $this->info(__('phrase.requesting-list-of-all-apps-from-steam'));
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
            $progress->setFormat("%current%/%max% %bar% %percent%%");

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
            $progress->setFormat("%current%/%max% %bar% %percent%%");

            // Insert the apps 500 at a time
            foreach ($chunkedApps as $apps) {
                SteamApp::insert($apps);
                $progress->advance();
            }
            $progress->finish();
            $this->info(PHP_EOL . __('phrase.steam-app-update-complete-x-added', ['x' => $fromSteamCount]));
        }
    }

    private function updateAppTypes()
    {
        // Get apps which do not have a type set
        $steamAppIds = SteamApp::whereNull('type')->pluck('id')->toArray();

        $this->info(__('phrase.requesting-type-for-x-apps-from-steam', ['x' => count($steamAppIds)]));

        $progress = $this->output->createProgressBar(count($steamAppIds));
        $progress->setFormat("%current%/%max% %bar% %percent%%");


        // Prevent hitting Steam's API rate limits of 200 requests every 5 minutes
        $storage  = new FileStorage(__DIR__ . "/../../../storage/app/api.bucket"); // store state in storage directory
        $rate     = new Rate(40, Rate::MINUTE); // add 40 tokens every minute (= 200 over 5 minutes)
        $bucket   = new TokenBucket(10, $rate, $storage); // bucket can never have more than 10 tokens saved up
        $consumer = new BlockingConsumer($bucket); // if no tokens are available, block further execution until there are tokens
        $bucket->bootstrap(10); // fill the bucket with 10 tokens initially

        $updatedCount = 0;
        foreach ($steamAppIds as &$appId) {
            // Query Steam API to get app details
            try {
                $consumer->consume(1);
                $app = Steam::app()->appDetails($appId);

            // If the API call failed, empty the bucket and skip the app
            } catch(ApiCallFailedException $e) {
                $consumer->consume(10);
                $progress->advance();
                continue;
            }
            if (isset($app[0])) {
                $type = $app[0]->type ?? null;
                $dlcs = $app[0]->dlc ?? [];
                $movies = $app[0]->movies ?? [];

                // If the app has a list of app IDs that are DLC, update their type now and remove them from the loop
                if (count($dlcs)) {
                    foreach ($dlcs as $dlcAppId) {
                        SteamApp::where('id', $dlcAppId)->update(['type' => 'dlc']);
                        $updatedCount++;
                        $key = array_search($dlcAppId, $steamAppIds);
                        if (false !== $key) {
                            unset($steamAppIds[$key]);
                            $progress->advance();
                        }
                    }
                }
                // If the app has a list of app IDs that are movies, update their type now and remove them from the loop
                if (count($movies)) {
                    foreach ($movies as $movie) {
                        SteamApp::where('id', $movie->id)->update(['type' => 'movie']);
                        $updatedCount++;
                        $key = array_search($movie->id, $steamAppIds);
                        if (false !== $key) {
                            unset($steamAppIds[$key]);
                            $progress->advance();
                        }
                    }
                }
            } else {
                $type = 'unknown';
            }
            SteamApp::where('id', $appId)->update(['type' => $type]);
            $updatedCount++;
            $progress->advance();
        }
        // Unset variable passed by reference
        unset($appId);
        $progress->finish();

        $this->info(PHP_EOL . __('phrase.steam-app-type-update-complete-x-apps-updated', ['x' => $updatedCount]));
    }

    private function exportCsv()
    {
        // Export apps table to CSV
        $steamApps = SteamApp::all()->toArray();
        $this->info(__('phrase.exporting-x-steam-apps-to-csv', ['x' => count($steamApps)]));
        $csv = Writer::createFromPath('steam_apps.csv', 'w+');
        $csv->insertAll($steamApps);
        $this->info(__('phrase.steam-app-csv-export-complete'));
    }
}
