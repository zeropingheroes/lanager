<?php

namespace Zeropingheroes\Lanager\Services;

use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\SteamUserApp;
use Zeropingheroes\Lanager\Models\User;

class GetGamesOwnedService
{
    /**
     * Get the top 10 games that users own.
     *
     * @return array
     */
    public function get(): array
    {
        // Get the LAN happening now, or the most recently ended LAN
        $lan = Lan::presentAndPast()
            ->orderBy('start', 'desc')
            ->first();

        if ($lan) {
            // Get the attendees for the LAN
            $users = $lan->users()->get();
        } else {
            // Or if there isn't a current LAN set, get all users
            $users = User::all();
        }

        $steamUserApps = SteamUserApp::with('user', 'app', 'user.steamMetadata', 'user.accounts')
            ->where('playtime_forever', '>', 60)
            ->whereIn('user_id', $users->pluck('id'))
            ->get();

        if (empty($steamUserApps)) {
            return [];
        }

        // Collect and combine games
        $usage = [];
        foreach ($steamUserApps as $app) {
            $usage[$app->steam_app_id] = $usage[$app->steam_app_id] ?? [
                    'game' => null,
                    'users' => [],
                ];
            $usage[$app->steam_app_id]['game'] = $usage[$app->steam_app_id]['game'] ?? $app->app;
            $usage[$app->steam_app_id]['users'][] = $app->user;
        }

        // Sort games array by user count, in descending order
        usort(
            $usage,
            function ($a, $b) {
                return count($b['users']) - count($a['users']);
            }
        );

        // Remove any recently played games that have only been played by one user
        $usage = array_filter(
            $usage,
            function ($game) {
                return count($game['users']) > 1;
            }
        );

        $usage = array_slice($usage, 0, 10);

        return $usage;
    }
}
