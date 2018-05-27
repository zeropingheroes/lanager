<?php

namespace Zeropingheroes\Lanager\Console\Commands;

use Illuminate\Console\Command;
use Zeropingheroes\Lanager\Lan;
use Zeropingheroes\Lanager\SteamUserState;

class PruneSteamUserHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lanager:prune-steam-user-history';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete historical Steam user status and gameplay data that did not occur:
    - during any of the LANs in the database, or
    - within the last five minutes';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info(__('phrase.pruning-historical-steam-data'));

        // For speed, only select the state's ID
        $statesToKeep = SteamUserState::select('*');

        // States created in the last 5 minutes
        $statesToKeep->where('created_at', '>=', now()->subMinutes(5));

        // States created during each LAN
        foreach (Lan::all() as $lan) {
            $statesToKeep->orWhere(
                function ($query) use ($lan) {
                    $query->where('created_at', '>', $lan->start);
                    $query->where('created_at', '<', $lan->end);
                }
            );
        }

        $statesToKeep = $statesToKeep->get()->pluck('id')->toArray();

        $quantityDeleted = SteamUserState::whereNotIn('id', $statesToKeep)
            ->delete();

        $quantityRemaining = SteamUserState::count();

        $this->info(__('phrase.x-entries-deleted-and-y-entries-retained', ['x' => $quantityDeleted, 'y' => $quantityRemaining]));
    }
}
