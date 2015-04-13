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

	/**
	 * Display info message with date and message, and log it
	 * @param  string $message info message to display & log
	 */
	public function info($message)
	{
		// Log message
		$this->log($message, 'info');

		// Display message
		parent::info( date($this->timestampFormat) . ' ' . $message);
	}

	/**
	 * Display error message with date and message, and log it
	 * @param  string $message Error message to display & log
	 */
	public function error($message)
	{
		// Log message
		$this->log($message, 'error');
		
		// Display message
		parent::error( date($this->timestampFormat) . ' ' . $message);
	}

	/**
	 * Log a message, attaching the command name
	 * @param  string $message Error message to display & log
	 * @param  string $level   Severity of the error
	 */
	public function log($message, $level = 'debug')
	{
		Log::{$level}( '[' . $this->name . '] ' . $message);
	}

}