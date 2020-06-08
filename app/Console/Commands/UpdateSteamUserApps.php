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
        $this->signature = 'lanager:update-steam-user-apps
                            {--all : ' . trans('phrase.update-all-users') . '}';
        $this->description = trans('phrase.update-existing-user-app-ownership');

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

        // If there is a current LAN, and the "update all users" option is not set
        if ($lan && ! $this->option('all')) {
            // Get the attendees for the LAN
            $users = $lan->users()->get();

        // Otherwise, get all users
        } else {
            $users = User::all();
        }

        if ($users->isEmpty()) {
            $message = trans('phrase.no-steam-users-to-update');
            Log::info($message);
            $this->info($message);
            return 0;
        }

        $this->info(trans('phrase.requesting-app-ownership-data-for-x-users-from-steam', ['x' => $users->count()]));

        // TODO: Add progress bar
        $service = new UpdateSteamUserAppsService($users);
        $service->update();

        $message = trans(
            'phrase.successfully-updated-app-ownership-data-for-x-of-y-users',
            ['x' => count($service->getUpdated()), 'y' => $users->count()]
        );
        Log::info($message, $service->getUpdated());
        $this->info($message);

        if ($service->errors()->isNotEmpty()) {
            $this->error(trans('phrase.the-following-errors-were-encountered'));
            foreach ($service->errors()->getMessages() as $error) {
                // TODO: find way of adding context to error
                Log::error($error[0]);
                $this->error($error[0]);
            }
            return 1;
        }
        return 0;
    }
}
