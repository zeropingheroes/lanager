<?php namespace Zeropingheroes\Lanager;

use Zeropingheroes\Lanager\Models\User,
	Zeropingheroes\Lanager\Models\SteamState,
	Zeropingheroes\Lanager\Models\Role,
	Zeropingheroes\Lanager\Interfaces\SteamUserRepositoryInterface;
use App, Auth, Input, Request, Redirect, View;
use LightOpenID;

class UsersController extends BaseController {

	protected $steamInterface;
	
	public function __construct(SteamUserRepositoryInterface $steamInterface)
	{
		$this->steamInterface = $steamInterface;
		$this->beforeFilter('checkResourcePermission',array('only' => array('create', 'store', 'edit', 'update', 'destroy') ));
	}


	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$users = User::visible()->orderBy('username', 'asc')->paginate(10);
		return View::make('users.list')
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
		if( $user = User::visible()->find($id) )
		{
			return View::make('users.show')
						->with('title',$user->username)
						->with('user',$user);
		}
		else
		{
			App::abort(404, 'User not found');
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
		User::visible()->destroy($id);
		return Redirect::route('users.index');
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
					$user = User::visible()->where('steam_id_64', '=', $steamId)->first();
					Auth::login($user);

					// Make the first user SuperAdmin
					if( count(User::all()) == 1 && ! $user->hasRole('SuperAdmin') )	$user->roles()->attach(Role::where('name', '=', 'SuperAdmin')->firstOrFail());

					if( $user->steam_visibility != 3 )
					{
						return Redirect::route('users.show', $user->id);
					}

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
		if( $user = User::visible()->find($id) )
		{
			$roles = Role::all();
			return View::make('users.roles')
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
		if( $user = User::visible()->find($id) )
		{
			$userRoles = (is_array(Input::get('userRoles')) ? Input::get('userRoles') : array() );
			$user->roles()->sync($userRoles);
			return Redirect::route('users.roles.edit',array('user' => $user->id));
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
		$steamUser = $this->steamInterface->getUser($steamId);		
		
		if($steamUser != NULL)
		{
			$user = User::where('steam_id_64', '=', $steamId)->first(); // do not constrain to visible users

			// Create new user if they are not found in the database
			if($user == NULL) $user = new User;

			$user->username 		= $steamUser->username;
			$user->steam_id_64		= $steamUser->id;
			$user->steam_visibility	= $steamUser->visibility;
			$user->visible			= true;	// make an invisible user visible again if they are returning to the lan
			$user->avatar			= $steamUser->avatar_url;
			$user->ip 				= Request::server('REMOTE_ADDR');

			return $user->save();
		}
		else
		{
			return FALSE;
		}
	}

}