<?php

namespace Zeropingheroes\Lanager\Http\Controllers\Api;

use Zeropingheroes\Lanager\Http\Controllers\Controller;
use Zeropingheroes\Lanager\Http\Resources\User as UserResource;
use Zeropingheroes\Lanager\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \Zeropingheroes\Lanager\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
