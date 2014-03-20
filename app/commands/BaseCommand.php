<?php namespace Zeropingheroes\Lanager\Commands;

use Illuminate\Console\Command;
use Log;

class BaseCommand extends Command {

	/**
	 * The timestamp format for console messages.
	 *
	 * @var string
	 */
	protected $timestampFormat = 'Y-m-d H:i:s';

	public function customInfo($message)
	{
		$this->info(date($this->timestampFormat).' '.$message);
		Log::info($this->name.' - '.$message);

	}

	public function customError($message)
	{
		$this->error(date($this->timestampFormat).' '.$message);
		Log::error($this->name.' - '.$message);
	}

}