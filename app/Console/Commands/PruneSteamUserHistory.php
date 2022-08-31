<?php

namespace Zeropingheroes\Lanager\Console\Commands;

use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Log;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\SteamUserAppSession;

class PruneSteamUserHistory extends Command
{
    /**
     * Set command signature and description.
     */
    public function __construct()
    {
        $this->signature = 'lanager:prune-steam-user-history';
        $this->description = trans('phrase.delete-steam-user-history-outside-lans');

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws Exception
     */
    public function handle()
    {
        $this->info(trans('phrase.pruning-historical-steam-data'));

        $periodsToDelete = [];

        // Get all past LANs, oldest to newest
        $lans = Lan::orderBy('end')->get();

        // Build an array of timestamps
        $previous = Carbon::createFromTimestampUTC(0)->toDateTimeString();

        foreach ($lans as $lan) {
            $periodsToDelete[] = ['start' => $previous, 'end' => $lan->start];
            $previous = $lan->end;
        }
        $periodsToDelete[] = ['start' => $previous, 'end' => Carbon::maxValue()];

        $statesToDelete = SteamUserAppSession::make();

        foreach ($periodsToDelete as $period) {
            $statesToDelete = $statesToDelete->orWhere(
                function ($query) use ($period) {
                    $query->where('updated_at', '>', $period['start']);
                    $query->where('updated_at', '<', $period['end']);
                }
            );
        }

        $quantityDeleted = $statesToDelete->delete();

        $quantityRemaining = SteamUserAppSession::count();

        $message = trans(
            'phrase.x-entries-deleted-and-y-entries-retained',
            ['x' => $quantityDeleted, 'y' => $quantityRemaining]
        );
        $this->info($message);
        Log::info($message);

        return 0;
    }
}
