<?php

namespace Zeropingheroes\Lanager\Console\Commands;

use DB;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;

class Backup extends Command
{
    /**
     * Set command signature and description.
     */
    public function __construct()
    {
        $this->signature = 'lanager:backup {output-dir : '.trans('phrase.output-dir').'}';
        $this->description = trans('phrase.backup-lanager-to-file');

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
        $imagesDir = base_path().'/storage/app/public/images';
        $outputDir = $this->argument('output-dir');
        $commitHash = trim(exec('cd '.base_path().' && /usr/bin/git log --pretty="%h" -n1 HEAD'));
        $filename = 'lanager-backup-'.date('Y-m-d_H-i-s').'-git-hash-'.$commitHash;

        if (! is_writable($outputDir)) {
            $this->error(trans('phrase.output-directory-not-writable'));
            die();
        }

        // Create a temporary directory in the output dir
        $processes['mkdir-tmp'] = new Process([
            'mkdir', '-p', "$outputDir/tmp/sql", "$outputDir/tmp/images",
        ]);

        // TODO: Use Laravel's filesystem class to get the files
        $processes['cp-images'] = Process::fromShellCommandline(
            "cp -r $imagesDir/* $outputDir/tmp/images/"
        );

        // Get all tables in database
        $tables = DB::select('SHOW TABLES');
        $tables = array_map('current', $tables);

        // Define process for dumping each one
        // TODO: Change how password is passed to mysqldump so it doesn't output warnings
        foreach ($tables as $table) {
            // Do not back up tables
            // migrations - will be created when migrations are re-run
            // steam_apps - will be restored from Steam API
            // logs - often very large
            // sessions - temporary
            if (in_array($table, ['migrations', 'steam_apps', 'logs', 'sessions', 'phpdebugbar'])) {
                continue;
            }
            $processes["mysqldump-$table"] = Process::fromShellCommandline(
                "mysqldump -u $username --password=$password --extended-insert=FALSE --no-create-info $database $table > $outputDir/tmp/sql/$table.sql"
            );
        }

        // Compress all files
        $processes['gzip'] = new Process([
            'tar', '-C', "$outputDir/tmp/", '-zcvf', "$outputDir/$filename.tar.gz", 'sql', 'images',
        ]);

        // Remove the temporary directory
        $processes['rm-tmp'] = new Process([
            'rm', '-rf', "$outputDir/tmp",
        ]);

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
            if (! $process->isSuccessful()) {
                $this->error(trans('phrase.process-exit-code-x', ['x' => $process->getExitCode()]));
                die();
            }
        }
        $this->info(trans('phrase.backup-created-successfully'));

        return 0;
    }
}
