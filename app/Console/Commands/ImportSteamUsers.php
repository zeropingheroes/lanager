<?php

namespace Zeropingheroes\Lanager\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Zeropingheroes\Lanager\Services\UpdateSteamUsersService;

class ImportSteamUsers extends Command
{
    /**
     * Set command signature and description.
     */
    public function __construct()
    {
        $this->signature = sprintf(
            'lanager:import-steam-users {steamIds* : %s}',
            trans('phrase.steamids-to-import-list-or-file')
        );
        $this->description = trans('phrase.import-users-from-steam-into-lanager');

        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $steamIds = $this->argument('steamIds');

        // Check if the argument is a file
        if (count($steamIds) == 1 && file_exists($steamIds[0])) {
            // Read Steam IDs from file into array
            $steamIds = file_get_contents($steamIds[0]);
            $steamIds = explode("\n", trim($steamIds));
        }

        if (! $steamIds) {
            $message = trans('phrase.no-steam-users-to-import');
            Log::error($message);
            $this->error($message);

            return 1;
        }

        $this->info(trans('phrase.importing-x-users-from-steam', ['x' => count($steamIds)]));

        $updateSteamUsersService = new UpdateSteamUsersService($steamIds);
        $updateSteamUsersService->update();

        $message = trans(
            'phrase.successfully-updated-x-of-y-users',
            ['x' => count($updateSteamUsersService->getUpdated()), 'y' => count($steamIds)]
        );
        Log::info($message);
        $this->info($message);

        if ($updateSteamUsersService->errors()->isNotEmpty()) {
            $this->error(trans('phrase.the-following-errors-were-encountered'));
            foreach ($updateSteamUsersService->errors()->getMessages() as $error) {
                Log::error($error[0]);
                $this->error($error[0]);
            }

            return 1;
        } else {
            return 0;
        }
    }
}
