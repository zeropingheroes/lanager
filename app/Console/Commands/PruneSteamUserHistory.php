<?php

namespace Zeropingheroes\Lanager\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Zeropingheroes\Lanager\Lan;
use Zeropingheroes\Lanager\SteamUserState;

class PruneSteamUserHistory extends Command
{
    /**
     * Set command signature and description
     */
    public function __construct()
    {
        $this->signature = 'lanager:prune-steam-user-history';
        $this->description = __('phrase.delete-steam-user-history-outside-lans-and-last-5-mins');

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info(__('phrase.pruning-historical-steam-data'));

        $periodsToDelete = [];

        // Get all past LANs, oldest to newest
        $lans = Lan::past()->orderBy('end')->get();

        // Build an array of timestamps
        $previous = Carbon::createFromTimestampUTC(0)->toDateTimeString();

        foreach ($lans as $lan) {
            $periodsToDelete[] = ['start' => $previous, 'end' => $lan->start];
            $previous = $lan->end;
        }
        $periodsToDelete[] = ['start' => $previous, 'end' => now()->subMinutes(5)->toDateTimeString()];

        $statesToDelete = SteamUserState::make();

        foreach ($periodsToDelete as $period) {
            $statesToDelete = $statesToDelete->orWhere(
                function ($query) use ($period) {
                    $query->where('created_at', '>', $period['start']);
                    $query->where('created_at', '<', $period['end']);
                }
            );
        }

        $quantityDeleted = $statesToDelete->delete();

        $quantityRemaining = SteamUserState::count();

        $message = __('phrase.x-entries-deleted-and-y-entries-retained', ['x' => $quantityDeleted, 'y' => $quantityRemaining]);
        $this->info($message);
        Log::info($message);
    }
}
