<?php

namespace Zeropingheroes\Lanager\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Zeropingheroes\Lanager\Services\UpdateSteamUserAppsService;
use Zeropingheroes\Lanager\User;
use Zeropingheroes\Lanager\Lan;

class UpdateSteamUserApps extends Command
{
    /**
     * Set command signature and description
     */
    public function __construct()
    {
        $this->signature = 'lanager:update-steam-user-apps';
        $this->description = __('phrase.update-existing-user-app-ownership');

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Exception
     * @throws \Throwable
     */
    public function handle()
    {
        // Get the LAN happening now, or the most recently ended LAN
        $lan = Lan::presentAndPast()
            ->orderBy('start', 'desc')
            ->first();

        if ($lan) {
            // Get the attendees for the LAN
            $users = $lan->users()->get();
        } else {
            // Or if there isn't a current LAN set, get all users
            $users = User::all();
        }

        if ($users->isEmpty()) {
            $message = __('phrase.no-steam-users-to-update');
            Log::info($message);
            $this->info($message);
            return;
        }

        $this->info(__('phrase.requesting-app-ownership-data-for-x-users-from-steam', ['x' => $users->count()]));

        $service = new UpdateSteamUserAppsService($users);
        $service->update();

        $message = __(
            'phrase.successfully-updated-app-ownership-data-for-x-of-y-users',
            ['x' => count($service->getUpdated()), 'y' => $users->count()]
        );
        Log::info($message, $service->getUpdated());
        $this->info($message);

        if ($service->errors()->isNotEmpty()) {
            $this->error(__('phrase.the-following-errors-were-encountered'));
            foreach ($service->errors()->getMessages() as $error) {
                // TODO: find way of adding context to error
                Log::error($error[0]);
                $this->error($error[0]);
            }
        }
    }

}
