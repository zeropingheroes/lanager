<?php

declare(strict_types=1);

namespace Zeropingheroes\Lanager\Requests;

use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\Validation\Validator;

trait LaravelValidation
{
    /**
     * Laravel validator class.
     */
    protected Validator $validator;

    /**
     * Validation rules for the Laravel validator.
     */
    protected array $validationRules = [];

    /**
     * Custom validation error messages for the Laravel validator.
     */
    protected array $validationMessages = [];

    /**
     * Validate the input against rules using Laravel's validation.
     */
    protected function laravelValidationPasses(): bool
    {
        $this->validator = ValidatorFacade::make($this->input, $this->validationRules, $this->validationMessages);

        if ($this->validator->fails()) {
            foreach ($this->validator->errors()->all() as $error) {
                $this->addError($error);
            }

            return false;
        }

        return true;
    }
}
