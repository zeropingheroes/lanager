<?php

namespace Zeropingheroes\Lanager\Http\Controllers\Api\Oauth;

use Zeropingheroes\Lanager\Http\Controllers\Controller;
use Zeropingheroes\Lanager\Http\Resources\User as UserResource;

class UserController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \Zeropingheroes\Lanager\User $user
     * @return UserResource
     */
    public function index()
    {
        $user = auth('api')->user()->load('accounts');
        return new UserResource($user);
    }
}
