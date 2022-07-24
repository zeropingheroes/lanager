<?php

namespace Zeropingheroes\Lanager\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Zeropingheroes\Lanager\Http\Controllers\Controller;
use Zeropingheroes\Lanager\Http\Resources\User as UserResource;
use Zeropingheroes\Lanager\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        if ($request->filled('ids')) {
            $ids = explode(',', $request->ids);

            return UserResource::collection(User::whereIn('id', $ids)->orderBy('username')->get());
        }

        return UserResource::collection(User::orderBy('username')->get());
    }

    /**
     * Display the specified resource.
     *
     * @param  User $user
     * @return UserResource
     */
    public function show(User $user)
    {
        $user->load('accounts');

        return new UserResource($user);
    }
}
