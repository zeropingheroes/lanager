<?php

namespace Zeropingheroes\Lanager\Requests;

use Illuminate\Support\Facades\Validator;

trait LaravelValidation
{
    /**
     * @var \Illuminate\Support\Facades\Validator
     */
    protected $validator;

    /**
     * Validation rules for the Laravel validator
     *
     * @var array
     */
    protected $validationRules = [];

    /**
     * Custom validation error messages for the Laravel validator
     *
     * @var array
     */
    protected $validationMessages = [];

    /**
     * Validate the input against rules using Laravel's validation
     *
     * @return bool
     */
    protected function laravelValidationPasses(): bool
    {
        $this->validator = Validator::make($this->input, $this->validationRules, $this->validationMessages);

        if ($this->validator->fails()) {
            foreach($this->validator->errors()->all() as $error)
            {
                $this->addError($error);
            }
            return false;
        }
        return true;
    }
}