<?php

namespace Zeropingheroes\Lanager\Requests;

use Illuminate\Support\MessageBag;

abstract class Request implements RequestContract
{
    /**
     * Request input data to be validated
     *
     * @var array
     */
    protected $input = [];

    /**
     * Errors
     *
     * @var \Illuminate\Support\MessageBag
     */
    protected $errors;

    /**
     * @var bool|null
     */
    protected $valid;

    /**
     * Instantiate the class with the request input
     *
     * @param array $input Request input data
     */
    public function __construct(array $input)
    {
        $this->input = $input;
        $this->errors = new MessageBag();
    }

    /**
     * Whether the request is valid
     *
     * @return bool
     */
    public function valid(): bool
    {
        // This method will be overridden by the subclass
        return false;
    }

    /**
     * Whether the request is invalid
     *
     * @return bool
     */
    public function invalid(): bool
    {
        // If validation has already been run
        // return the result of the validation
        if ($this->valid != null) {
            return !$this->valid;
        }

        // Otherwise run validation and return the result (inverse)
        return !$this->valid();
    }

    /**
     * @param bool $valid
     * @return bool
     */
    protected function setValid(bool $valid): bool
    {
        $this->valid = $valid;
        return $this->valid;
    }

    /**
     * Request errors
     *
     * @return MessageBag
     */
    public function errors(): MessageBag
    {
        return $this->errors;
    }
}