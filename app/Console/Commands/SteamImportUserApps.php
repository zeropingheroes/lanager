<?php

namespace Zeropingheroes\Lanager\Console\Commands;

use Illuminate\Console\Command;
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
        $userIds = User::all()->pluck('id');

        $this->info(__('phrase.requesting-games-owned-by-count-users-from-steam', ['count' => count($userIds)]));

        $service = new SteamUserAppImportService($userIds->toArray());
        $service->import();

//        $message = __('phrase.successfully-imported-apps-for-x-of-y-users', ['x' => count($service->getImported()), 'y' => count($userIds)]);
//        Log::info($message);
//        $this->info($message);
//
//        if ($service->errors()->isNotEmpty()) {
//            $this->error(__('phrase.the-following-errors-were-encountered'));
//            foreach ($service->errors()->getMessages() as $error) {
//                Log::error($error[0]);
//                $this->error($error[0]);
//            }
//        }
    }

}
