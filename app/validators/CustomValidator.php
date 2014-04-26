<?php namespace Zeropingheroes\Lanager\Validators;

use Config, Auth;
use Illuminate\Validation\Validator;
use ExpressiveDate;
use Carbon\Carbon;

class CustomValidator extends Validator {

	/**
	 * Check to see if user has submitted an item more recently than the flood protect time
	 * 
	 * @param  $attribute
	 * @param  $value
	 * @param  $parameters 
	 * @return boolean
	 */	
	public function validateFloodProtect($attribute, $value, $parameters)
	{
		$date = new ExpressiveDate;
		$date->minusSeconds(Config::get('lanager/floodprotect.'.$parameters[0]));
		return ! Auth::user()->{$parameters[0]}()->where('created_at','>',$date)->count();
	}

	/**
	 * Check to see if end date comes after start date
	 * 
	 * @param  $attribute
	 * @param  $value
	 * @param  $parameters 
	 * @return boolean
	 */
	public function validateDateNotBeforeThisInput($attribute, $value, $parameters)
	{
		if( !empty($value) )
		{
			$start = $this->getValue($parameters[0]); // get the value of the parameter (start)

			$start = Carbon::createFromFormat('d/m/Y H:i',$start)->timestamp;
			$end = Carbon::createFromFormat('d/m/Y H:i',$value)->timestamp;

			return ($end > $start);
		}
		return false;
	}

	protected function replaceDateNotBeforeThisInput($message, $attribute, $rule, $parameters)
	{
		return str_replace(':other', str_replace('_', ' ', $parameters[0]), $message);
	}

}