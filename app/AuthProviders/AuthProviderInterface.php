<?php

namespace Zeropingheroes\Lanager\AuthProviders;

use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Contracts\User;

interface AuthProviderInterface
{
    public function to(): RedirectResponse;

    public function from(): User;

    public function getName(): string;
}