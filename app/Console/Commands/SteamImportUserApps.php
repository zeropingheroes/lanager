<?php

namespace Zeropingheroes\Lanager\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Zeropingheroes\Lanager\Services\SteamUserAppImportService;
use Zeropingheroes\Lanager\User;

class SteamImportUserApps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'steam:import-user-apps';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Steam apps owned by existing LANager users';

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
            $users = Cache::get('currentLan')->users()->get()->pluck('id');
        } else {
            // Or if there isn't a current LAN set, get all users
            $users = User::all()->pluck('user_id');
        }

        $this->info(__('phrase.requesting-games-owned-by-count-users-from-steam', ['count' => count($users)]));

        $service = new SteamUserAppImportService($users->toArray());
        $service->import();

        $message = __('phrase.successfully-imported-apps-for-x-of-y-users', ['x' => count($service->getImported()), 'y' => count($users)]);
        Log::info($message);
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
