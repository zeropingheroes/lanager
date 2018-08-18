<?php

namespace Zeropingheroes\Lanager\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class RestoreBackup extends Command
{
    /**
     * Set command signature and description
     */
    public function __construct()
    {
        $this->signature = 'lanager:restore-backup
                            {backup-file : '.__('phrase.backup-file').'}
                            {--yes : '.__('phrase.suppress-confirmations').'}';

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
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $database = env('DB_DATABASE');
        $imagesDir = base_path() . '/storage/app/public/images';
        $backupFile = $this->argument('backup-file');
        $restoreDir = '/tmp/lanager-backup-restore-' . date('Y-m-d_H-i-s');

        if (!file_exists($backupFile)) {
            $this->error(__('phrase.backup-file-not-found'));
            die();
        }

        $this->warn(__('phrase.this-will-delete-all-lanager-data'));

        if ($this->option('yes') || $this->confirm(__('phrase.are-you-sure'))) {
            $this->info(__('phrase.deleting-all-lanager-data'));
        } else {
            die();
        }

        // Delete existing images
        $processes["rm-images"] = new Process(
            "rm -rf $imagesDir/*"
        );

        // Clear database
        $this->call('migrate:fresh');

        // Import Steam apps
        $this->call('lanager:update-steam-apps');

        // Create a temporary restore directory
        $processes["mkdir-$restoreDir"] = new Process(
            "mkdir -p $restoreDir"
        );

        // Extract all files to temporary directory
        $processes["uncompress"] = new Process(
            "tar -zxvf $backupFile -C $restoreDir"
        );

        // Restore images
        $processes["cp-images"] = new Process(
            "cp $restoreDir/images/* $imagesDir"
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
                die();
            }
        }
        $this->info(__('phrase.backup-restored-successfully'));
    }
}
