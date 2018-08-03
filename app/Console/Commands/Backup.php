<?php

namespace Zeropingheroes\Lanager\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;

class Backup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lanager:backup
                            {output-dir : Where to store the backup file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Back up LANager data to a file';

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
        $outputDir = $this->argument('output-dir');
        $backupName = 'lanager-backup-' . date('Y-m-d_H-i-s');

        if (!is_writable($outputDir)) {
            $this->error(__('phrase.output-directory-not-writable'));
            die();
        }

        // Create a temporary directory in the output dir
        $processes["mkdir-$backupName"] = new Process(
            "mkdir -p $outputDir/$backupName/sql $outputDir/$backupName/images"
        );

        // TODO: Use Laravel's filesystem class to get the files
        $processes["cp-images"] = new Process(
            "cp $imagesDir/* $outputDir/$backupName/images/"
        );

        // Get all tables in database
        $tables = DB::select('SHOW TABLES');
        $tables = array_map('current', $tables);

        // Define process for dumping each one
        // TODO: Change how password is passed to mysqldump so it doesn't output warnings
        foreach ($tables as $table) {
            $processes["mysqldump-$table"] = new Process(
                "mysqldump -u $username --password=$password --extended-insert=FALSE --no-create-info $database $table > $outputDir/$backupName/sql/$table.sql"
            );
        }

        // Zip all files
        $processes["zip"] = new Process(
            "zip -r $outputDir/$backupName.zip $outputDir/$backupName/*"
        );

        // Remove the temporary directory
        $processes["rm-tmp"] = new Process(
            "rm -rf $outputDir/$backupName"
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
                $this->error(__('phrase.process-exit-code', ['x' => $process->getExitCode()]));
                die();
            }
        }
        $this->info(__('phrase.backup-created-successfully'));
    }
}
