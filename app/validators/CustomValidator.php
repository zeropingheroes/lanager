<?php namespace Zeropingheroes\Lanager\Validators;

use Illuminate\Validation\Validator;
use Carbon\Carbon;

class CustomValidator extends Validator {


	/**
	 * Validate the date is before a given date.
	 *
	 * @param  string  $attribute
	 * @param  mixed   $value
	 * @param  array   $parameters
	 * @return bool
	 */
	protected function validate_before($attribute, $value, $parameters)
	{
		/*
		* If a input with the name equal to the value we compare with, we
		* use it, otherwise we proceed as usual
		*/

		if( isset( $this->attributes[ $parameters[0] ] ) )
		{
			$value_to_compare = $this->attributes[ $parameters[0] ];
		}
		else
		{
			$value_to_compare = $parameters[0];
		}

		return ( strtotime( $value ) < strtotime( $value_to_compare ) );
	}

	/**
	 * Validate the date is after a given date.
	 *
	 * @param  string  $attribute
	 * @param  mixed   $value
	 * @param  array   $parameters
	 * @return bool
	 */
	protected function validate_after($attribute, $value, $parameters)
	{
		/*
		* If a input with the name equal to the value we compare with, we
		* use it, otherwise we proceed as usual
		*/

		if( isset( $this->attributes[ $parameters[0] ] ) )
		{
			$value_to_compare = $this->attributes[ $parameters[0] ];
		}
		else
		{
			$value_to_compare = $parameters[0];
		}
		return ( strtotime( $value ) > strtotime( $value_to_compare ) );
	}

}