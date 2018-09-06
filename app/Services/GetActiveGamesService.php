<?php

namespace Zeropingheroes\Lanager\Services;

use Zeropingheroes\Lanager\SteamUserAppSession;
use Zeropingheroes\Lanager\SteamUserState;
use Illuminate\Support\Collection;

class GetActiveGamesService
{
    /**
     * Get the games that are currently being played
     *
     * @return Collection
     */
    public function get(): Collection
    {
        $sessions = SteamUserAppSession::whereNull('end')
            ->with('user', 'app', 'user.accounts', 'user.steamMetadata')
            ->get();

        if (empty($sessions)) {
            return new Collection();
        }

        // Collect and combine states for the same game
        $combinedUsage = [];
        foreach ($sessions as $session) {
            $combinedUsage[$session->steam_app_id] = $combinedUsage[$session->steam_app_id] ?? ['game' => null, 'users' => []];
            $combinedUsage[$session->steam_app_id]['game'] = $combinedUsage[$session->steam_app_id]['game'] ?? $session->app;
            $combinedUsage[$session->steam_app_id]['users'][] = $session->user;
        }

        // Sort games array by user count, in descending order (removing key)
        usort(
            $combinedUsage,
            function ($a, $b) {
                return count($b['users']) - count($a['users']);
            }
        );
        return collect($combinedUsage);
    }
}