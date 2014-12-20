<?php namespace Zeropingheroes\Lanager\Users;

use Illuminate\Http\Request;
use Dingo\Api\Routing\Route;
use Dingo\Api\Auth\AuthorizationProvider;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Config, Auth;

class UserApiKeyAuthorizationProvider extends AuthorizationProvider
{
	public function authenticate(Request $request, Route $route)
	{
		// verify that Authorization header is for this provider
		$this->validateAuthorizationHeader($request);

		// extract API key from header
		$apikey = trim(strstr($request->header('authorization'), ' '));
		
		// verify that api key is 32 char hexadecimal string
		if ( strlen($apikey) != 32 ) throw new UnauthorizedHttpException(null);
		if ( ! ctype_xdigit($apikey) ) throw new UnauthorizedHttpException(null);
		
		// attempt to find user in database with given api key
		try
		{
			$user = User::where('api_key', $apikey)->firstOrFail();
			
			// "Log the user in" for this request without making a session so that authority lib can work
			Config::set('session.driver', 'array');
			Auth::login($user);

			return $user;
		}
		catch (ModelNotFoundException $e)
		{
			// reject auth if no user found with given key
			throw new UnauthorizedHttpException(null);
		}
	}

	public function getAuthorizationMethod()
	{
		return 'lanager';
	}
}