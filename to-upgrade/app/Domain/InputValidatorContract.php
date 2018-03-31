<?php namespace Zeropingheroes\Lanager\Domain;

interface InputValidatorContract
{
    /**
     * Test if the input data passes validation
     * @return bool
     */
    public function passes();

    /**
     * Test if the input data fails validation
     * @return bool
     */
    public function fails();

}