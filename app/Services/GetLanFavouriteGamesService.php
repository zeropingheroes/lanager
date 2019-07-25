<?php

namespace Zeropingheroes\Lanager\Services;

use Illuminate\Support\Collection;
use Zeropingheroes\Lanager\Lan;

class GetLanFavouriteGamesService
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
        $favourites = $this->lan->userFavouriteGames()->with('user', 'favouriteable')->get();

        if (empty($favourites)) {
            return new Collection();
        }

        // Collect and combine favourites for the same game
        $combined = [];
        foreach ($favourites as $favourite) {
            $combined[$favourite->favouriteable_id] = $combined[$favourite->favouriteable_id] ?? ['game' => null, 'users' => []];
            $combined[$favourite->favouriteable_id]['game'] = $combined[$favourite->favouriteable_id]['game'] ?? $favourite->favouriteable;
            $combined[$favourite->favouriteable_id]['favourites'][] = $favourite;
        }

        // Sort games array by user count, in descending order (removing key)
        usort(
            $combined,
            function ($a, $b) {
                return count($b['favourites']) - count($a['favourites']);
            }
        );
        return collect($combined);
    }
}