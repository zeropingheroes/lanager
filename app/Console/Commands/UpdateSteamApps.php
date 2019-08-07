<?php

namespace Zeropingheroes\Lanager\Console\Commands;

use Illuminate\Console\Command;
use Syntax\SteamApi\Exceptions\ApiCallFailedException;
use Syntax\SteamApi\Facades\SteamApi as Steam;
use Zeropingheroes\Lanager\SteamApp;
use League\Csv\Writer;
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
//        $this->updateAppList();
        $this->updateAppTypes();
        $this->exportCsv();
        return;
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
                $type = $app[0]->type;
                $dlcs = $app[0]->dlc;

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
