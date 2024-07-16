<?php

namespace Zeropingheroes\Lanager\Requests;

interface RequestContract
{
    /**
     * Instantiate the class with the request input.
     */
    public function __construct(array $input);

    /**
     * Whether the request is valid.
     */
    public function valid(): bool;

    /**
     * Whether the request is invalid.
     */
    public function invalid(): bool;

    /**
     * Request errors.
     */
    public function errors(): array;
}
