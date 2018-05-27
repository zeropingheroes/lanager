<?php

namespace Zeropingheroes\Lanager\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Zeropingheroes\Lanager\Services\UpdateSteamUsersService;

class ImportSteamUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lanager:import-steam-users
                            {steamIds* : One or more SteamId64(s) for the user(s) to import, or a file containing a list of IDs}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import users from Steam into LANager';

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Exception
     */
    public function handle()
    {
        $steamIds = $this->argument('steamIds');

        // Check if the argument is a file
        if (count($steamIds) == 1 && file_exists($steamIds[0])) {
            // Read Steam IDs from file into array
            $steamIds = file_get_contents($steamIds[0]);
            $steamIds = explode("\n", trim($steamIds));
        }

        if (!$steamIds) {
            $message = __('phrase.no-steam-users-to-import');
            Log::error($message);
            $this->error($message);
            return;
        }

        $this->info(__('phrase.importing-x-users-from-steam', ['x' => count($steamIds)]));

        $service = new UpdateSteamUsersService($steamIds);
        $service->update();

        $message = __(
            'phrase.successfully-updated-x-of-y-users',
            ['x' => count($service->getUpdated()), 'y' => count($steamIds)]
        );
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
