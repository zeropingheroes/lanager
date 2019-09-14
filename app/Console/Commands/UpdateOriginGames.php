<?php

namespace Zeropingheroes\Lanager\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Zeropingheroes\Lanager\OriginGame;

class UpdateOriginGames extends Command
{

    /** @var string The base API url
     */
    private const API_URL = 'https://api2.origin.com/xsearch/store/en_us/gbr/';

    /**
     * Set command signature and description
     */
    public function __construct()
    {
        $this->signature = 'lanager:update-origin-games';
        $this->description = __('phrase.update-origin-games');

        $this->client = new Client(['base_uri' => $this::API_URL]);

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info(__('phrase.requesting-list-of-all-games-from-origin-api'));

        $response = $this->client->request(
            'GET',
            'products',
            [
                'query' =>
                    [
                        'filterQuery' => 'gameType:basegame',
                        'sort' => 'title asc',
                        'start' => '0',
                        'rows' => '1', // Max 30
                        'isGDP' => 'true',
                    ]
            ]
        );

        $responseBody = json_decode($response->getBody());
        $totalGames = $responseBody->games->numFound;

        $games = [];

        for ($start = 0; $start <= $totalGames; $start = $start + 30) {
            $response = $this->client->request(
                'GET',
                'products',
                [
                    'query' =>
                        [
                            'filterQuery' => 'gameType:basegame',
                            'sort' => 'title asc',
                            'start' => $start,
                            'rows' => '30',
                            'isGDP' => 'true',
                        ]
                ]
            );
            $games = array_merge($games, json_decode($response->getBody())->games->game);
        }

        // Get free games
        $response = $this->client->request(
            'GET',
            'products',
            [
                'query' =>
                    [
                        'filterQuery' => 'gameType:freegames',
                        'sort' => 'title asc',
                        'start' => 0,
                        'rows' => '30',
                        'isGDP' => 'true',
                    ]
            ]
        );

        // Add free games to total
        $responseBody = json_decode($response->getBody());
        $totalGames = $totalGames + $responseBody->games->numFound;
        $games = array_merge($games, $responseBody->games->game);

        $this->info(__('phrase.updating-x-origin-games', ['x' => $totalGames]));

        $updatedCount = 0;

        foreach ($games as $game) {
            $databaseApp = OriginGame::updateOrCreate(
                ['name' => $game->gameName],
                ['url' => 'https://www.origin.com/store' . $game->path]
            );
            if ($databaseApp->wasChanged() || $databaseApp->wasRecentlyCreated) {
                $updatedCount++;
            }
        }
        $this->info(__('phrase.x-origin-games-updated', ['x' => $updatedCount]));
    }
}
