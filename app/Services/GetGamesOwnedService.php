<?php

declare(strict_types=1);

namespace Zeropingheroes\Lanager\Services;

use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\SteamUserApp;
use Zeropingheroes\Lanager\Models\User;

class GetGamesOwnedService
{
    /**
     * Get the top games that users own.
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
            ->where('playtime_forever', '>', 60)
            ->whereIn('user_id', $users->pluck('id'))
            ->orderBy('playtime_forever', 'desc')
            ->get();

        // Collect and combine games
        $usage = [];
        foreach ($steamUserApps as $steamUserApp) {
            $usage[$steamUserApp->steam_app_id] ??= [
                'game' => null,
                'users' => [],
            ];
            $usage[$steamUserApp->steam_app_id]['game'] ??= $steamUserApp->app;
            $usage[$steamUserApp->steam_app_id]['users'][] = $steamUserApp->user;
        }

        // Sort games array by user count, in descending order
        usort(
            $usage,
            fn ($a, $b) => count($b['users']) - count($a['users'])
        );

        return array_slice($usage, 0, $count);
    }
}
