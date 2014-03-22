<?php namespace Zeropingheroes\Lanager;

use Zeropingheroes\Lanager\Models\User,
	Zeropingheroes\Lanager\Models\SteamState,
	Zeropingheroes\Lanager\Models\Role,
	Zeropingheroes\Lanager\Interfaces\SteamUserRepositoryInterface;
use App, Auth, Input, Request, Redirect, View;
use LightOpenID;

class UsersController extends BaseController {

	protected $steamUsers;
	
	public function __construct(SteamUserRepositoryInterface $steamUsers)
	{
		$this->steamUsers = $steamUsers;
		$this->beforeFilter('checkResourcePermission',array('only' => array('create', 'store', 'edit', 'update', 'destroy') ));
	}


	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$users = User::orderBy('username', 'asc')->paginate(10);
		return View::make('user.list')
					->with('title','People')
					->with('users',$users);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  object  SteamUser $steamUser
	 * @return Response
	 */
	public function store($steamUser)
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		if( $user = User::find($id) )
		{
			return View::make('user.show')
						->with('title',$user->username)
						->with('user',$user);
		}
		else
		{
			App::abort(404, 'Page not found');
		}
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		User::destroy($id);
		return Redirect::route('user.index');
	}

	/**
	 * Authenticate the user using OpenID
	 *
	 * @return Redirect
	 */
	public function openIdLogin()
	{
		if(Input::has('openid_mode'))
		{
			$openId = new LightOpenID(Request::server('HTTP_HOST'));
	
			if($openId->validate())
			{
				$steamId = str_replace('http://steamcommunity.com/openid/id/','',$openId->identity);
				
				if($this->importSteamUser($steamId))
				{
					$user = User::where('steam_id_64', '=', $steamId)->first();
					Auth::login($user);

					// Make the first user SuperAdmin
					if( count(User::all()) == 1 && ! $user->hasRole('SuperAdmin') )	$user->roles()->attach(Role::where('name', '=', 'SuperAdmin')->firstOrFail());

					return Redirect::to('/');
				}
				else
				{
					App::abort(500, 'Could not import user from Steam into database.');
				}
			}
			else
			{
				App::abort(500, 'Could not validate OpenID.');
			}
		}
		else
		{
			App::abort(400, 'Bad request.');
		}
	}

	/**
	 * Log the user out
	 *
	 * @return Redirect
	 */
	public function logout()
	{
		Auth::logout();
		return Redirect::back();
	}

	/**
	 * Show the form for editing the specified user's roles.
	 *
	 * @return Redirect
	 */
	public function editRoles($id)
	{
		if( $user = User::find($id) )
		{
			$roles = Role::all();
			return View::make('user.roles')
							->with('title', $user->username.' - Roles')
							->with('user', $user)
							->with('roles', $roles);
		}
		else
		{
			App::abort(404, 'Page not found');
		}
	}

	/**
	 * Update the specified user's roles in storage.
	 *
	 * @return Redirect
	 */
	public function updateRoles($id)
	{
		if( $user = User::find($id) )
		{
			$userRoles = (is_array(Input::get('userRoles')) ? Input::get('userRoles') : array() );
			$user->roles()->sync($userRoles);
			return Redirect::route('user.roles.edit',array('user' => $user->id));
		}
		else
		{
			App::abort(404, 'Page not found');
		}
	}

	/**
	 * Import the specified Steam user
	 *
	 * @return bool
	 */
	private function importSteamUser($steamId)
	{		
		$steamUser = $this->steamUsers->getUser($steamId);		
		
		if($steamUser != NULL)
		{
			$user = User::where('steam_id_64', '=', $steamId)->first();

			// Create new user if they are not found in the database
			if($user == NULL) $user = new User;

			$user->username 	= $steamUser->username;
			$user->steam_id_64	= $steamUser->id;
			$user->avatar		= $steamUser->avatar_url;
			$user->ip 			= Request::server('REMOTE_ADDR');

			return $user->save();
		}
		else
		{
			return FALSE;
		}
	}

}