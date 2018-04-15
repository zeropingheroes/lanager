<?php

namespace Zeropingheroes\Lanager\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Zeropingheroes\Lanager\SteamApp;
use Zeropingheroes\Lanager\SteamAppServer;
use Zeropingheroes\Lanager\SteamUserState;
use Zeropingheroes\Lanager\User;
use Zeropingheroes\Lanager\UserOAuthAccount;
use Steam;

class SteamImportUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'steam:import-users
                            {steamIds?* : One or more SteamId64(s) for the user(s) to import, or a file containing a list of IDs. If omitted, import up-to-date user information for existing users}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import user information from Steam';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // If the Steam ID argument is present
        if ($this->argument('steamIds')) {
            $steamIds = $this->argument('steamIds');

            // Check if the argument is a file
            if (count($steamIds) == 1 AND file_exists($steamIds[0])) {
                // Read Steam IDs from file into array
                $steamIds = file_get_contents($steamIds[0]);
                $steamIds = explode("\n", trim($steamIds));
            }

            // Remove excess white space and convert strings to integers
            $steamIds = array_map(function ($steamId) {
                    return intval(trim($steamId));
                },
                $steamIds);
        } else {
            // If no Steam IDs are given as a command argument
            // Get existing Steam IDs from database
            $steamIds = UserOAuthAccount::where('provider', 'steam')->get()->pluck('provider_id')->toArray();
        }

        if (count($steamIds) == 0) {
            $this->info(__('phrase.no-steam-users-found-to-import'));
            return;
        }

        $this->info(__('phrase.requesting-current-status-of-count-users-from-steam', ['count' => count($steamIds)]));

        // TODO: Refactor all below behaviour into service class
        $steamUsers = Steam::user($steamIds)->GetPlayerSummaries();

        // Initialise counter
        $successCount = 0;

        // Import state for each user in turn
        foreach ($steamUsers as $steamUser) {

            try {
                // Create a new state
                $steamUserState = new SteamUserState;

                // Check if the Steam account already exists in the database
                $userOAuthAccount = UserOAuthAccount::where('provider_id', $steamUser->steamId)->first();

                if (!$userOAuthAccount) {
                    // If this Steam account is not already in the database
                    // Create a new LANager user account
                    $user = User::create(['username' => $steamUser->personaName]);

                } else {
                    // If this Steam account is in the database
                    // Get the associated user
                    $user = $userOAuthAccount->user;
                }

                // Update the existing Steam account
                // or create it, if it does not yet exist
                $userOAuthAccount = $user->OAuthAccounts()
                    ->updateOrCreate(
                        [
                            'username' => $steamUser->personaName,
                            'avatar' => $steamUser->avatarMediumUrl,
                            'provider' => 'steam',
                            'provider_id' => $steamUser->steamId,
                        ]
                    );

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

                    // Get the server
                    // ... or if the server has not been previously recorded, create it
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
        }

        $this->info(
            __('phrase.successfully-imported-states-for-x-of-y-users', ['x' => $successCount, 'y' => count($steamUsers)])
        );
    }
}
