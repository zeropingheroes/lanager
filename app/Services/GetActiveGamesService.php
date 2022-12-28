<?php

namespace Zeropingheroes\Lanager\Services;

use Illuminate\Support\Collection;
use Zeropingheroes\Lanager\Models\SteamUserAppSession;

class GetActiveGamesService
{
    /**
     * Get the games that are currently being played.
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
        $usage = [];
        foreach ($sessions as $session) {
            $usage[$session->steam_app_id] = $usage[$session->steam_app_id] ?? [
                    'game' => null,
                    'users' => []
                ];
            $usage[$session->steam_app_id]['game'] = $usage[$session->steam_app_id]['game'] ?? $session->app;
            $usage[$session->steam_app_id]['users'][] = $session->user;
        }

        // Sort games array by user count, in descending order (removing key)
        usort(
            $usage,
            function ($a, $b) {
                return count($b['users']) - count($a['users']);
            }
        );

        return collect($usage);
    }
}
