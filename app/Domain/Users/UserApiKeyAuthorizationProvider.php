<?php namespace Zeropingheroes\Lanager\Domain\Users;

use Illuminate\Http\Request;
use Dingo\Api\Routing\Route;
use Dingo\Api\Auth\AuthorizationProvider;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Auth;

class UserApiKeyAuthorizationProvider extends AuthorizationProvider
{
	/**
	 * Authenticate a user's request using their API key
	 * @param  Request   $request Request to the app
	 * @param  Route     $route   Route into the app
	 * @return BaseModel          User object if successfully authenticated
	 * @throws BadHttpException            When API key format incorrect
	 * @throws UnauthorizedHttpException   When no user found with given API key
	 */
	public function authenticate(Request $request, Route $route)
	{
		// verify that Authorization header is for this provider
		$this->validateAuthorizationHeader($request);

		// extract API key from header
		$apikey = trim(strstr($request->header('authorization'), ' '));
		
		// verify that api key is 32 char hexadecimal string
		if ( strlen($apikey) != 32 ) throw new UnauthorizedHttpException(null, 401);
		if ( ! ctype_xdigit($apikey) ) throw new UnauthorizedHttpException(null, 401);
		
		// attempt to find user in database with given api key
		try
		{
			$user = User::where('api_key', $apikey)->firstOrFail();
			
			// Log the user in once for this request without making a session so that authority lib can work
			Auth::onceUsingId($user->id);

			return $user;
		}
		catch (ModelNotFoundException $e)
		{
			// reject auth if no user found with given key
			throw new UnauthorizedHttpException(null, 401);
		}
	}

	/**
	 * Name the authorisation method
	 * @return string
	 */
	public function getAuthorizationMethod()
	{
		return 'lanager';
	}
}