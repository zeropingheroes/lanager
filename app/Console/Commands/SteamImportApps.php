<?php

namespace Zeropingheroes\Lanager\Console\Commands;

use Illuminate\Console\Command;
use Steam;
use Zeropingheroes\Lanager\SteamApp;

class SteamImportApps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'steam:import-apps';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieve and store all Steam applications';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info(__('phrase.requesting-apps-from-steam'));
        $apps = Steam::app()->GetAppList();

        $existingCount = SteamApp::count();

        // If the database is empty, do a bulk insert
        if ($existingCount == 0) {

            $this->info(__('phrase.preparing-data'));

            $progress = $this->output->createProgressBar(count($apps));
            $progress->setFormat("%bar% %percent%%");

            foreach ($apps as $key => $app) {
                $apps[$key] = ['id' => $app->appid, 'name' => $app->name];
                $progress->advance();
            }
            $progress->finish();

            $this->info(PHP_EOL . __('phrase.importing-apps-from-steam', ['count' => count($apps)]));

            // Chunk the apps into blocks of 500
            $chunkedApps = array_chunk($apps, 500);

            $progress = $this->output->createProgressBar(count($chunkedApps));
            $progress->setFormat("%bar% %percent%%");

            // Insert the apps 500 at a time
            foreach ($chunkedApps as $apps) {
                SteamApp::insert($apps);
                $progress->advance();
            }
            $progress->finish();

        } else {
            // Otherwise check and update/insert each app individually (slower but safer)
            $this->info(__('phrase.updating-apps-in-db-and-adding-new', ['count' => $existingCount]));

            $progress = $this->output->createProgressBar(count($apps));
            $progress->setFormat("%bar% %percent%%");

            foreach ($apps as $app) {
                SteamApp::updateOrCreate(['id' => $app->appid, 'name' => $app->name]);
                $progress->advance();
            }
            $progress->finish();
        }

        $this->info(PHP_EOL . __('phrase.import-complete'));
    }
}
