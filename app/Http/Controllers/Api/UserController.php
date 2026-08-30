<?php

declare(strict_types=1);

namespace Zeropingheroes\Lanager\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Zeropingheroes\Lanager\Http\Controllers\Controller;
use Zeropingheroes\Lanager\Http\Resources\UserResource;
use Zeropingheroes\Lanager\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        if ($request->filled('ids')) {
            $ids = explode(',', $request->ids ?? '');

            return UserResource::collection(User::whereIn('id', $ids)->orderBy('username')->get());
        }

        return UserResource::collection(User::orderBy('username')->get());
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user, Request $request): UserResource
    {
        $user->load('accounts');

        if ($request->has('lans')) {
            $user->load('lans');
        }

        return new UserResource($user);
    }
}
