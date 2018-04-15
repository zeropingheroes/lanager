<?php

namespace Zeropingheroes\Lanager\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Zeropingheroes\Lanager\SteamApp;
use Zeropingheroes\Lanager\SteamAppServer;
use Zeropingheroes\Lanager\SteamUserState;
use Zeropingheroes\Lanager\UserOAuthAccount;
use Steam;

class SteamImportUserStates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'steam:import-user-states';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieve and store Steam user status information for all registered users';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Get an array of Steam IDs to query Steam with
        $steamIds = UserOAuthAccount::where('provider', 'steam')->get()->pluck('provider_id');

        if (count($steamIds) == 0) {
            $this->info(__('phrase.no-users-found'));
            return;
        }
        $this->info(__('phrase.requesting-current-status-of-count-users-from-steam', ['count' => count($steamIds)]));

        $steamUsers = Steam::user($steamIds->toArray())->GetPlayerSummaries();

        // Initialise counter
        $successCount = 0;

        // Import state for each user in turn
        foreach ($steamUsers as $steamUser) {

            try {

                // Create a new state
                $steamUserState = new SteamUserState;

                // Find the OAuh account corresponding to this Steam user
                // TODO: consider firstOrCreate to allow easy importing of Steam accounts
                $userOAuthAccount = UserOAuthAccount::where('provider_id', $steamUser->steamId)->first();

                // Update the account details
                $userOAuthAccount->username = $steamUser->personaName;
                $userOAuthAccount->user->username = $steamUser->personaName;
                $userOAuthAccount->avatar = $steamUser->avatarMediumUrl;
                $userOAuthAccount->user->save();
                $userOAuthAccount->save();

                // Associate the state with the user
                $steamUserState->user()->associate($userOAuthAccount->user);

                // Get the app they are running, if any
                if ($steamUser->gameDetails) {
                    $steamApp = SteamApp::find($steamUser->gameDetails->gameId);

                    // Associate the state with the app
                    $steamUserState->app()->associate($steamApp);
                }

                // Get the server they are connected to, if any
                if ($steamUser->gameDetails && $steamUser->gameDetails->serverIp) {

                    // TODO: make this safe for IPv6 addresses
                    $ipAndPort = explode(':', $steamUser->gameDetails->serverIp);
                    $ip = $ipAndPort[0];
                    $port = $ipAndPort[1];

                    // Get the server, or if the server has not been previously recorded, create it
                    $steamAppServer = SteamAppServer::firstOrCreate(
                        [
                            'steam_app_id' => $steamApp->id,
                            'address' => $ip,
                            'port' => $port
                        ]
                    );

                    // Associate the state with the server
                    $steamUserState->server()->associate($steamAppServer);
                }

                // Set the user's online status
                $steamUserState->online_status = $steamUser->personaStateId;

                $steamUserState->save();

                $successCount++;

            } catch (Exception $e) {
                $this->error(__('phrase.unable-to-import-state-for-user', ['id' => $steamUser->id, 'error' => $e->getMessage()]));
            }

            $this->info(__('phrase.successfully-imported-states-for-x-of-y-users', ['x' => $successCount, 'y' => count($steamUsers)]));
        }
    }
}
