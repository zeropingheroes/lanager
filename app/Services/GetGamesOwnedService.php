<?php

namespace Zeropingheroes\Lanager\Services;

use Zeropingheroes\Lanager\SteamUserApp;

class GetGamesOwnedService
{
    /**
     * Get the top 10 games that users own
     *
     * @return array
     */
    public function get(): array
    {
        $steamUserApps = SteamUserApp::with('user', 'app', 'user.state', 'user.OAuthAccounts')
            ->where('playtime_forever', '>', 60)->get();

        if (empty($steamUserApps)) {
            return [];
        }

        // Collect and combine games
        $combinedUsage = [];
        foreach ($steamUserApps as $steamUserApp) {
            $combinedUsage[$steamUserApp->steam_app_id] = $combinedUsage[$steamUserApp->steam_app_id] ?? [
                    'game' => null,
                    'users' => []
                ];
            $combinedUsage[$steamUserApp->steam_app_id]['game'] = $combinedUsage[$steamUserApp->steam_app_id]['game'] ?? $steamUserApp->app;
            $combinedUsage[$steamUserApp->steam_app_id]['users'][] = $steamUserApp->user;
        }

        // Sort games array by user count, in descending order
        usort(
            $combinedUsage,
            function ($a, $b) {
                return count($b['users']) - count($a['users']);
            }
        );

        // Remove any recently played games that have only been played by one user
        $combinedUsage = array_filter(
            $combinedUsage,
            function ($game) {
                return count($game['users']) > 1;
            }
        );

        $combinedUsage = array_slice($combinedUsage, 0, 10);

        return $combinedUsage;
    }

}