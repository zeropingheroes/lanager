<?php

namespace Zeropingheroes\Lanager\Services;

use Illuminate\Support\Collection;
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
        $combined = [];
        foreach ($picks as $pick) {

            // TODO: work for aggregating with provider too
            $combined[$pick->game_id] = $combined[$pick->game_id] ?? ['game' => null];

            $combined[$pick->game_id]['game'] = $combined[$pick->game_id]['game'] ?? $pick->game;

            $combined[$pick->game_id]['picks'][] = $pick;
        }

        // Sort games array by user count, in descending order (removing key)
        usort(
            $combined,
            function ($a, $b) {
                return count($b['picks']) - count($a['picks']);
            }
        );
        return collect($combined);
    }
}