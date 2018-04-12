<?php

namespace Zeropingheroes\Lanager\Requests;

use Illuminate\Support\Facades\Validator;
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
     * Validator instance
     *
     * @var \Illuminate\Contracts\Validation\Validator
     */
    protected $validator;

    /**
     * Validation rules
     *
     * @var array
     */
    protected $rules = [];

    /**
     * Instantiate the class with the request input
     *
     * @param array $input Request input data
     */
    public function __construct(array $input)
    {
        $this->input = $input;

        // Create the validator instance with the input and rules
        // but do not run validation yet - leave this to the subclass
        $this->validator = Validator::make($this->input, $this->rules);
    }

    /**
     * Whether the request is invalid
     *
     * @return bool
     */
    public function invalid(): bool
    {
        return !$this->valid();
    }

    /**
     * Request errors
     *
     * @return MessageBag
     */
    public function errors(): MessageBag
    {
        return $this->validator->errors();
    }
}