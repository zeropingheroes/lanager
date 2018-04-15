<?php

namespace Zeropingheroes\Lanager\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
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
        $steamIds = UserOAuthAccount::where('provider','steam')->get()->pluck('provider_id');

        if(count($steamIds) == 0) {
            $this->info(__('phrase.no-users-found'));
            return;
        }
        $this->info(__('phrase.requesting-current-status-of-count-users-from-steam', ['count' => count($steamIds)]));

        $steamUsers = Steam::user($steamIds->toArray())->GetPlayerSummaries();

        foreach($steamUsers as $steamUser) {
            // Find the OAuh account corresponding to this Steam user
            $userOAuthAccount = UserOAuthAccount::where('provider_id', $steamUser->steamId)->first();

            // Update the account
            $userOAuthAccount->username = $steamUser->personaName;
            $userOAuthAccount->user->username = $steamUser->personaName;
            $userOAuthAccount->avatar = $steamUser->avatarMediumUrl;
            $userOAuthAccount->user->save();
            $userOAuthAccount->save();

        }

    }
}
