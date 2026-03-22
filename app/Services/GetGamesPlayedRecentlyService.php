<?php

namespace Zeropingheroes\Lanager\Services;

use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\SteamUserApp;
use Zeropingheroes\Lanager\Models\User;

class GetGamesPlayedRecentlyService
{
    /**
     * Get the top games that users have played in the last 2 weeks.
     */
    public function get(int $count = 10): array
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
            ->where('playtime_two_weeks', '>', 0)
            ->whereIn('user_id', $users->pluck('id'))
            ->orderBy('playtime_two_weeks', 'desc')
            ->get();

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

        return array_slice($usage, 0, $count);
    }
}
