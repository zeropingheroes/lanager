<?php

namespace Zeropingheroes\Lanager\Console\Commands;

use bandwidthThrottle\tokenBucket\BlockingConsumer;
use bandwidthThrottle\tokenBucket\Rate;
use bandwidthThrottle\tokenBucket\storage\FileStorage;
use bandwidthThrottle\tokenBucket\storage\StorageException;
use bandwidthThrottle\tokenBucket\TimeoutException;
use bandwidthThrottle\tokenBucket\TokenBucket;
use Carbon\CarbonInterval;
use Illuminate\Console\Command;
use Log;
use Syntax\SteamApi\Exceptions\ApiCallFailedException;
use Zeropingheroes\Lanager\SteamApp;

class UpdateSteamAppsMetadata extends Command
{
    /**
     * Set command signature and description
     */
    public function __construct()
    {
        $this->signature = 'lanager:update-steam-apps-metadata '
                         . '{--all-apps : ' . trans('phrase.update-all-apps') . '}';
        $this->description = trans('phrase.update-steam-apps-metadata');

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws TimeoutException
     * @throws StorageException
     */
    public function handle()
    {
        if (!SteamApp::count()) {
            $message = trans('phrase.database-empty-aborting');
            $this->error($message);
            Log::error($message);
            return 1;
        }

        if ( $this->option('all-apps')) {
            // Get all apps
            $steamAppIds = SteamApp::all()->pluck('id')->toArray();
        } else {
            // Get apps which do not have a type set
            $steamAppIds = SteamApp::whereNull('type')->pluck('id')->toArray();
        }

        $appCount = count($steamAppIds);
        if (!$appCount) {
            $message = trans('phrase.steam-app-metadata-up-to-date');
            $this->info($message);
            Log::info($message);
            return 0;
        }

        $timeEstimate = CarbonInterval::seconds(ceil($appCount*1.5));

        $this->info(trans('phrase.requesting-metadata-for-x-apps-from-steam-api', ['x' => $appCount]));
        $this->info(trans('phrase.this-will-take-approximately-time-to-complete', ['time' => $timeEstimate->cascade()->forHumans()]));

        $progress = $this->output->createProgressBar($appCount);
        $progress->setFormat('%current%/%max% %bar% %percent%% - %elapsed% ' . trans('title.elapsed'));

        // Prevent hitting Steam's API rate limits of 200 requests every 5 minutes
        $storage = new FileStorage(storage_path('steam-web-api.bucket')); // store state in storage directory
        $rate = new Rate(40, Rate::MINUTE); // add 40 tokens every minute (= 200 over 5 minutes)
        $bucket = new TokenBucket(200, $rate, $storage); // bucket can never have more than 200 tokens saved up
        $consumer = new BlockingConsumer($bucket); // if no tokens are available, block further execution until there are tokens
        $bucket->bootstrap(200); // fill the bucket with 200 tokens initially

        $updatedCount = 0;
        $failedCount = 0;
        foreach ($steamAppIds as &$appId) {
            // Query Steam API to get app details
            try {
                $consumer->consume(1);
                $app = SteamApi::app()->appDetails($appId);

                // If the API call failed, empty the bucket and skip the app
            } catch (ApiCallFailedException $e) {
                $failedCount++;
                $consumer->consume(10);
                $message = trans('phrase.error-updating-metadata-for-steam-app-id-message', ['id' => $appId, 'message' => $e->getMessage()]);
                $this->error($message);
                Log::error($message);
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

        $message = trans('phrase.x-steam-apps-updated', ['x' => $updatedCount]);
        $this->info(PHP_EOL .$message);
        Log::info($message);

        if ($failedCount) {
            $message = trans('phrase.x-steam-apps-not-updated-re-run-command', ['x' => $failedCount]);
            $this->error($message);
            Log::error($message);
            return 1;
        }
        return 0;
    }
}
