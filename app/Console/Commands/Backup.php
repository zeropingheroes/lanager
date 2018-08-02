<?php

namespace Zeropingheroes\Lanager\Console\Commands;

use FilesystemIterator;
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
        $outputDir = $this->argument('output-dir');
        $filename = 'lanager-backup-'.date('Y-m-d_H-i-s');

        if (!is_writable($outputDir)) {
            $this->error(__('phrase.output-directory-not-writable'));
            die();
        }

        // Create a temporary directory in the output dir
        $processes["mkdir-tmp"] = new Process(
            "mkdir -p $outputDir/tmp/"
        );

        // Get all tables in database
        $tables = DB::select('SHOW TABLES');
        $tables = array_map('current', $tables);

        // Define process for dumping each one
        foreach ($tables as $table) {
            $processes["mysqldump-$table"] = new Process(
                "mysqldump -u $username --password=$password --extended-insert=FALSE --no-create-info $database $table > $outputDir/tmp/$table.sql"
            );
        }

        // Zip all SQL files
        $processes["zip-sql"] = new Process(
            "zip -r $outputDir/$filename.zip $outputDir/tmp/*.sql"
        );

        // Remove the temporary directory
        $processes["rm-sql"] = new Process(
            "rm -rf $outputDir/tmp"
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
