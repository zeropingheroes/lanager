<?php

namespace Zeropingheroes\Lanager\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Zeropingheroes\Lanager\Lan;

class GetLanAttendeeGamePicksService
{
    /**
     * @var Lan
     */
    private $lan;

    public function __construct(Lan $lan)
    {
        $this->lan = $lan;
    }

    /**
     * Get the games that are currently being played
     *
     * @return Collection
     */
    public function get(): Collection
    {
        $picks = $this->lan->attendeeGamePicks()->with('user', 'game')->get();

        if (empty($picks)) {
            return new Collection();
        }

        // Collect and combine picks for the same game
        $games = [];
        foreach ($picks as $pick) {
            $id = $pick->game_provider.$pick->game_id;
            $games[$id]['name'] = $pick->game->name;
            $games[$id]['logo'] = $pick->game->logo();
            $games[$id]['url'] = $pick->game->url();
            $games[$id]['provider'] = 'steam_app';
            $games[$id]['id'] = $pick->game->id;
            if (Auth::user() && Auth::user()->id === $pick->user->id) {
                $games[$id]['auth_user_pick'] = $pick;
            }
            $games[$id]['picks'][] = $pick;
        }

        // Sort games array by number of picks, in descending order (removing key)
        usort(
            $games,
            function ($a, $b) {
                return count($b['picks']) - count($a['picks']);
            }
        );
        return collect($games);
//        dd(collect($games));
    }
}