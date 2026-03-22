<?php

namespace Zeropingheroes\Lanager\Requests;

abstract class Request implements RequestContract
{
    /**
     * Request input data to be validated.
     */
    protected array $input = [];

    /**
     * Errors.
     */
    protected array $errors = [];

    /**
     * Whether the request is valid.
     */
    protected bool $valid = false;

    /**
     * Instantiate the class with the request input.
     */
    public function __construct(array $input)
    {
        $this->input = $input;
    }

    /**
     * Whether the request is valid.
     */
    public function valid(): bool
    {
        // This method will be overridden by the subclass
        return false;
    }

    /**
     * Whether the request is invalid.
     */
    public function invalid(): bool
    {
        // If validation has already been run
        // return the result of the validation
        if ($this->valid != null) {
            return ! $this->valid;
        }

        // Otherwise run validation and return the result (inverse)
        return ! $this->valid();
    }

    /**
     * Set the request to "valid"
     */
    protected function setValid(bool $valid): bool
    {
        $this->valid = $valid;

        return $this->valid;
    }

    /**
     * Add an error to the error array
     */
    protected function addError(string $error): void
    {
        $this->errors[] = $error;
    }

    /**
     * Get the request errors.
     */
    public function errors(): array
    {
        return $this->errors;
    }
}
