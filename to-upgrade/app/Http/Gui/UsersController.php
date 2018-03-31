<?php namespace Zeropingheroes\Lanager\Http\Gui;

use Redirect;
use View;
use Zeropingheroes\Lanager\Domain\Users\UserService;

class UsersController extends ResourceServiceController
{

    /**
     * Set the controller's service
     */
    public function __construct()
    {
        $this->service = new UserService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $users = $this->service->all();

        return View::make('users.index')
            ->with('title', 'Users')
            ->with('users', $users);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $user = $this->service->single($id);

        return View::make('users.show')
            ->with('title', $user->username)
            ->with('user', $user);
    }

}