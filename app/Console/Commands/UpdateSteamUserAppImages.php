<?php

namespace Zeropingheroes\Lanager\Console\Commands;

use Saloon\Exceptions\Request\Statuses\NotFoundException;
use Saloon\RateLimitPlugin\Exceptions\RateLimitReachedException;
use Zeropingheroes\SteamApis\SteamStoreApi\SteamStoreApiConnector;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Zeropingheroes\Lanager\Models\SteamApp;
use Illuminate\Http\Client\Response;

class UpdateSteamUserAppImages extends Command
{
    /**
     * Set command signature and description.
     */
    public function __construct()
    {
        $this->signature = 'lanager:update-steam-user-app-images
        {--limit=} : ' . trans('phrase.limit-number-of-apps-to-update');
        $this->description = trans('phrase.update-app-images-for-apps-played-by-users');

        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $appsWithOwners = SteamApp::has('owners')->get();
        $appsWithPlayers = SteamApp::has('players')->get();

        $apps = $appsWithOwners->merge($appsWithPlayers);

        // TODO: Improve prioritisation of apps with no logo URLs
        $apps = $apps->sortBy([
            ['updated_at', 'asc'], // NULL first, then oldest updated_at to newest
            ['id', 'asc'], // Oldest apps first
        ]);

        $steamCdnBaseUrl = 'https://cdn.akamai.steamstatic.com/steam/apps/';

        $successfulAppCount = 0;
        $failedApps = new Collection();
        $processedAppCount = 0;

        $limit = $this->option('limit');
        if ($limit) {
            $apps = $apps->take($limit);
        }

        $this->info('Updating ' . $apps->count() . ' apps');

        foreach ($apps as $app) {
            $processedAppCount++;

            if ($app->logo_small && $app->logo_medium && $app->logo_large) {
                // TODO: Check one by one and on first 404 move to next code block?
                if (
                    $this->requestLogoUrl($app->logo_small)->status() == 200 &&
                    $this->requestLogoUrl($app->logo_medium)->status() == 200 &&
                    $this->requestLogoUrl($app->logo_large)->status() == 200
                ) {
                    $this->info(
                        'App ' . $app->id . ' (' . $app->name . '): logo URLs from database are accessible. Skipping.'
                    );
                    $app->touch();
                    $successfulAppCount++;
                    continue;
                }
            }
            $this->info('App ' . $app->id . ' (' . $app->name . '): No logo URLs set. Checking default URL...');

            $defaultSmallLogoUrl = $steamCdnBaseUrl . $app->id . '/capsule_184x69.jpg';
            $defaultMediumLogoUrl = $steamCdnBaseUrl . $app->id . '/header_292x136.jpg';
            $defaultLargeLogoUrl = $steamCdnBaseUrl . $app->id . '/header.jpg';

            if ($this->requestLogoUrl($defaultSmallLogoUrl)->status() == 200) {
                $app->logo_small = $defaultSmallLogoUrl;
                $app->logo_medium = $defaultMediumLogoUrl;
                $app->logo_large = $defaultLargeLogoUrl;
                $this->info('App ' . $app->id . ' (' . $app->name . '): Default logo URL accessible. Saving...');
                $successfulAppCount++;
            } else {
                $this->warn(
                    'App ' . $app->id . ' (' . $app->name . '): Default logo URL not accessible. Querying API...'
                );
                $logoUrls = $this->getAppLogoUrlsFromApi($app->id);
                if ($logoUrls) {
                    $this->info('App ' . $app->id . ' (' . $app->name . '): Got logo URLs from API. Saving...');
                    $app->logo_small = $logoUrls['small'];
                    $app->logo_medium = $logoUrls['medium'];
                    $app->logo_large = $logoUrls['large'];
                    $successfulAppCount++;
                } else {
                    $this->warn(
                        'App ' . $app->id . ' (' . $app->name . '): Failed to get logo URLs from API. Skipping.'
                    );
                    $app->touch();
                    $failedApps = $failedApps->push($app);
                    continue;
                }
            }

            $app->save();
        }

        if ($successfulAppCount > 0) {
            $this->info('Successfully updated logo image URLs for ' . $successfulAppCount . ' apps.');

            if ($failedApps->count() > 0) {
                $this->warn('Failed to update logo image URLs for ' . $failedApps->count() . ' apps:');
                foreach ($failedApps as $failedApp) {
                    $this->warn('• App ' . $failedApp->id . ': ' . $failedApp->name);
                }
                return 1;
            }
            return 0;
        } else {
            $this->warn('Failed to update logo image URLs for ' . $processedAppCount . ' apps.');
            return 1;
        }
    }

    private function requestLogoUrl(string $url): Response
    {
        return Http::head($url);
    }

    private function getAppLogoUrlsFromApi(int $appId): array
    {
        $steamStoreApi = app(SteamStoreApiConnector::class);

        $maxAttempts = 3;
        $attempt = 0;

        while ($attempt < $maxAttempts) {
            $attempt++;

            try {
                $appDetails = $steamStoreApi->appDetails($appId);

                return [
                    'small' => $appDetails->capsule_imagev5,
                    'medium' => $appDetails->capsule_image,
                    'large' => $appDetails->header_image,
                ];
            } catch (NotFoundException $e) {
                return [];
            } catch (RateLimitReachedException $e) {
                $seconds = (int) $e->getLimit()->getRemainingSeconds();

                // If the limiter returns 0/negative, still back off a bit to avoid a tight loop.
                if ($seconds < 1) {
                    $seconds = 1;
                }
                if ($attempt >= $maxAttempts) {
                    $this->warn('Rate limit exceeded and max retry attempts reached.');
                    break;
                }

                $this->warn(
                    'Rate limit exceeded. Waiting ' . $seconds .
                    ' seconds before retrying (attempt ' . $attempt . ' of ' . $maxAttempts . ')...'
                );
                sleep($seconds);
            }
        }
        return [];
    }
}
