<?php

namespace Zeropingheroes\Lanager\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Zeropingheroes\Lanager\Services\UpdateSteamUserAppsService;
use Zeropingheroes\Lanager\User;

class UpdateSteamUserApps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lanager:update-steam-user-apps';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update existing LANager users\' app ownership data with the latest information from their Steam profile';

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Exception
     * @throws \Throwable
     */
    public function handle()
    {
        // If there's a current LAN set
        if(Cache::get('currentLan')) {
            // Get the attendees for the current LAN
            $users = Cache::get('currentLan')->users()->get();
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

        $message = __('phrase.successfully-updated-app-ownership-data-for-x-of-y-users', ['x' => count($service->getUpdated()), 'y' => $users->count()]);
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
