<?php

namespace Zeropingheroes\Lanager\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Zeropingheroes\Lanager\Services\SteamUserImportService;
use Zeropingheroes\Lanager\UserOAuthAccount;

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
     * @throws \Exception
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
        } else {
            // If no Steam IDs are given as a command argument
            // Get existing Steam IDs from database
            $steamIds = UserOAuthAccount::where('provider', 'steam')->get()->pluck('provider_id')->toArray();
        }

        $this->info(__('phrase.requesting-current-status-of-count-users-from-steam', ['count' => count($steamIds)]));

        $service = new SteamUserImportService($steamIds);
        $service->import();

        $message = __('phrase.successfully-imported-states-for-x-of-y-users', ['x' => count($service->getImported()), 'y' => count($steamIds)]);
        Log::info($message);
        $this->info($message);

        if ($service->errors()->isNotEmpty()) {
            $this->error(__('phrase.the-following-errors-were-encountered'));
            foreach ($service->errors()->getMessages() as $error) {
                Log::error($error[0]);
                $this->error($error[0]);
            }
        }
    }
}
