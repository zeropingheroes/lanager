<?php

namespace Zeropingheroes\Lanager\Console\Commands;

use Astrotomic\SteamSdk\SteamConnector;
use Illuminate\Console\Command;
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
        $this->signature = 'lanager:update-steam-user-app-images';
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

        // TODO: Order by app age / playtime / number of owners
        $apps = $appsWithOwners->merge($appsWithPlayers);

        $this->info('Updating ' . $apps->count() . ' apps');

        $steamCdnBaseUrl = 'https://cdn.akamai.steamstatic.com/steam/apps/';

        foreach ($apps as $app) {
            if ($app->logo_small && $app->logo_medium && $app->logo_large) {
                // TODO: Check one by one and on first 404 move to next code block?
                if (
                    $this->requestLogoUrl($app->logo_small)->status() == 200 &&
                    $this->requestLogoUrl($app->logo_medium)->status() == 200 &&
                    $this->requestLogoUrl($app->logo_large)->status() == 200
                ) {
                    $this->info(
                        '✅ App ' . $app->id . ' (' . $app->name . '): logo URLs from database are accessible. Skipping.'
                    );
                    continue;
                }
            }
            $this->info('ℹ️ App ' . $app->id . ' (' . $app->name . '): No logo URLs set. Checking default URL...');

            $defaultSmallLogoUrl = $steamCdnBaseUrl . $app->id . '/capsule_184x69.jpg';
            $defaultMediumLogoUrl = $steamCdnBaseUrl . $app->id . '/header_292x136.jpg';
            $defaultLargeLogoUrl = $steamCdnBaseUrl . $app->id . '/header.jpg';

            if ($this->requestLogoUrl($defaultSmallLogoUrl)->status() == 200) {
                $app->logo_small = $defaultSmallLogoUrl;
                $app->logo_medium = $defaultMediumLogoUrl;
                $app->logo_large = $defaultLargeLogoUrl;
                $this->info('✅ App ' . $app->id . ' (' . $app->name . '): Default logo URL accessible. Saving...');
            } else {
                $this->warn(
                    '⚠️ App ' . $app->id . ' (' . $app->name . '): Default logo URL not accessible. Querying API...'
                );
                $logoUrls = $this->getAppLogoUrlsFromApi($app->id);
                if ($logoUrls) {
                    $this->info('✅ App ' . $app->id . ' (' . $app->name . '): Got logo URLs from API. Saving...');
                    $app->logo_small = $logoUrls['small'];
                    $app->logo_medium = $logoUrls['medium'];
                    $app->logo_large = $logoUrls['large'];
                } else {
                    // TODO: Count failed apps (and mark in DB so we don't unnecessarily retry?)
                    $this->error(
                        '❌ App ' . $app->id . ' (' . $app->name . '): Failed to get logo URLs from API. Skipping.'
                    );
                    continue;
                }
            }

            $app->save();
        }

        return 0;
    }

    private function requestLogoUrl(string $url): Response
    {
        return Http::head($url);
    }

    private function getAppLogoUrlsFromApi(int $appId): array
    {
        $steamApi = app(SteamConnector::class);
        // TODO: Handle/pace 429 errors (potentially in library)
        try {
            $appDetails = $steamApi->appDetails($appId);
            return [
                'small' => $appDetails->capsule_imagev5,
                'medium' => $appDetails->capsule_image,
                'large' => $appDetails->header_image
            ];
        } catch (\Spatie\LaravelData\Exceptions\CannotCreateData $e) {
            return [];
        }
    }
}
