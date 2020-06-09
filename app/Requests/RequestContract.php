<?php

namespace Zeropingheroes\Lanager\Requests;

interface RequestContract
{
    /**
     * Instantiate the class with the request input.
     *
     * @param array $input Request input data
     */
    public function __construct(array $input);

    /**
     * Whether the request is valid.
     *
     * @return bool
     */
    public function valid(): bool;

    /**
     * Whether the request is invalid.
     *
     * @return bool
     */
    public function invalid(): bool;

    /**
     * Request errors.
     *
     * @return array
     */
    public function errors(): array;
}
