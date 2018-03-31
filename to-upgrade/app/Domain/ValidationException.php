<?php namespace Zeropingheroes\Lanager\Domain;

use Exception;

class ValidationException extends Exception
{
    private $validationErrors;

    public function __construct(
        $message,
        $validationErrors = []
    ) {
        parent::__construct($message);

        $this->validationErrors = $validationErrors;
    }

    public function getValidationErrors()
    {
        return $this->validationErrors;
    }
}