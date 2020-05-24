<?php

namespace Zeropingheroes\Lanager\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Cache;

class RestoreBackup extends Command
{
    /**
     * Set command signature and description
     */
    public function __construct()
    {
        $this->signature = 'lanager:restore-backup
                            {backup-file : ' . __('phrase.backup-file') . '}
                            {--yes : ' . __('phrase.suppress-confirmations') . '}';

        $this->description = __('phrase.restore-lanager-backup-from-file');

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $database = config('database.connections.mysql.database');
        $imagesDir = base_path() . '/storage/app/public/images';
        $backupFile = $this->argument('backup-file');
        $restoreDir = '/tmp/lanager-backup-restore-' . date('Y-m-d_H-i-s');

        if (!file_exists($backupFile)) {
            $this->error(__('phrase.backup-file-not-found'));
            return 1;
        }

        $this->warn(__('phrase.this-will-delete-all-lanager-data'));

        if ($this->option('yes') || $this->confirm(__('phrase.are-you-sure'))) {
            $this->info(__('phrase.deleting-all-lanager-data'));
        } else {
            return 1;
        }

        // Delete existing images
        $processes["rm-images"] = new Process(
            "rm -rf $imagesDir/*"
        );

        // Clear database
        $this->call('migrate:fresh');

        // Import Steam apps
        $this->call('lanager:import-steam-apps-csv');

        // Create a temporary restore directory
        $processes["mkdir-$restoreDir"] = new Process(
            "mkdir -p $restoreDir"
        );

        // Extract all files to temporary directory
        $processes["uncompress"] = new Process(
            "tar -zxvf $backupFile -C $restoreDir"
        );

        // Create the images directory
        $processes["mkdir-$imagesDir"] = new Process(
            "mkdir -p $imagesDir"
        );

        // Restore images
        $processes["cp-images"] = new Process(
            "cp -r $restoreDir/images/* $imagesDir"
        );

        // Restore database dump files
        // TODO: Change how password is passed to mysql so it doesn't output warnings
        $processes["mysql-restore"] = new Process(
            "cat $restoreDir/sql/*.sql | mysql --init-command=\"SET SESSION FOREIGN_KEY_CHECKS=0;\" -u $username --password=$password $database"
        );

        // Remove the temporary directory
        $processes["rm-tmp"] = new Process(
            "rm -rf $restoreDir"
        );

        // Run the defined processes in turn
        foreach ($processes as $process) {
            $process->setTimeout(300);
            $process->run(
                function ($type, $buffer) {
                    if (Process::ERR === $type) {
                        $this->error(str_replace(["\r", "\n"], '', $buffer));
                    } else {
                        $this->line(str_replace(["\r", "\n"], '', $buffer));
                    }
                }
            );
            if (!$process->isSuccessful()) {
                $this->error(__('phrase.process-exit-code-x', ['x' => $process->getExitCode()]));
                return $process->getExitCode();
            }
        }

        // Clear navigation link cache
        Cache::forget('navigationLinks');

        $this->info(__('phrase.backup-restored-successfully'));
        return 0;
    }
}
