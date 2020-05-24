<?php

namespace Zeropingheroes\Lanager\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Zeropingheroes\Lanager\Lan;
use Zeropingheroes\Lanager\Services\UpdateSteamUsersService;
use Zeropingheroes\Lanager\SteamUserMetadata;
use Zeropingheroes\Lanager\User;
use Zeropingheroes\Lanager\UserOAuthAccount;

class UpdateSteamUsers extends Command
{
    /**
     * Set command signature and description
     */
    public function __construct()
    {
        $this->signature = 'lanager:update-steam-users
                            {--all : ' . __('phrase.update-all-users') . '}';
        $this->description = __('phrase.update-existing-users-profiles-from-steam');

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Exception
     */
    public function handle()
    {
        // Get the LAN happening now, or the most recently ended LAN
        $lan = Lan::presentAndPast()
            ->orderBy('start', 'desc')
            ->first();

        // If there is a current LAN, and the "update all users" option is not set
        if ($lan && ! $this->option('all')) {
            // Get the attendees for the LAN
            $attendees = $lan->users()->get()->pluck('id');

            // Also get any users who have not been updated in the last day
            $staleUsers = SteamUserMetadata::whereNotIn('user_id', $attendees)
                ->where('profile_updated_at', '<=', now()->subDay())
                ->get()
                ->pluck('user_id');

            $users = $attendees->merge($staleUsers);

        // Otherwise, get all users
        } else {
            $users = User::all()->pluck('id');
        }

        // Get the Steam IDs belonging to the users who are to be updated
        $steamIds = UserOAuthAccount::whereIn('user_id', $users)
            ->get()
            ->pluck('provider_id')
            ->toArray();

        if (!$steamIds) {
            $message = __('phrase.no-steam-users-to-update');
            Log::info($message);
            $this->info($message);
            return 0;
        }

        $this->info(__('phrase.updating-profiles-and-online-status-for-x-users-from-steam', ['x' => count($steamIds)]));

        // TODO: Add progress bar
        $service = new UpdateSteamUsersService($steamIds);
        $service->update();

        $message = __(
            'phrase.successfully-updated-profiles-and-online-status-for-x-of-y-users',
            ['x' => count($service->getUpdated()), 'y' => count($steamIds)]
        );
        Log::info($message, $service->getUpdated());
        $this->info($message);

        if ($service->errors()->isNotEmpty()) {
            $this->error(__('phrase.the-following-errors-were-encountered'));
            foreach ($service->errors()->getMessages() as $error) {
                Log::error($error[0]);
                $this->error($error[0]);
            }
            return 1;
        }
        return 0;
    }
}
