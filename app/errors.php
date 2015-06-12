<?php

/*
|--------------------------------------------------------------------------
| Handle Uncaught Exceptions
|--------------------------------------------------------------------------
*/
App::error( function( Exception $exception, $code )
{
	Log::error( 'Uncaught exception: ' . get_class( $exception ), [ $exception->__toString() ] );
	
	// Display pretty HTTP 500 page if we aren't in debug mode (and don't log it again)
	if ( ! Config::get('app.debug') ) return handleHttpError( 500, ['log' => false] );
});

/*
|--------------------------------------------------------------------------
| Handle all HTTP Status Codes
|--------------------------------------------------------------------------
*/
App::error( function( Symfony\Component\HttpKernel\Exception\HttpException $exception, $httpStatusCode )
{
	return handleHttpError( $httpStatusCode, ['source' => 'gui'] );
});

/*
|--------------------------------------------------------------------------
| Handle Unauthorized Requests (API Access)
|--------------------------------------------------------------------------
*/
API::error( function( Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException $exception )
{
	return handleHttpError( 401, ['source' => 'api'] );
});

/*
|--------------------------------------------------------------------------
| Handle Model Not Found (GUI Access)
|--------------------------------------------------------------------------
*/
App::error( function( Illuminate\Database\Eloquent\ModelNotFoundException $exception )
{
	// Treat as HTTP 404
	return handleHttpError( 404, ['source' => 'gui'] );
});

/*
|--------------------------------------------------------------------------
| Handle Unauthorised (GUI Access)
|--------------------------------------------------------------------------
*/
App::error( function( Zeropingheroes\Lanager\Domain\AuthorisationException $exception )
{
	// Treat as HTTP 403
	return handleHttpError( 403, ['source' => 'gui', 'description' => $exception->getMessage()] );
});

/*
|--------------------------------------------------------------------------
| Handle Unauthorised (API Access)
|--------------------------------------------------------------------------
*/
API::error( function( Zeropingheroes\Lanager\Domain\AuthorisationException $exception )
{
	// Treat as HTTP 403
	return handleHttpError( 403, ['source' => 'api', 'description' => $exception->getMessage()] );
});

/*
|--------------------------------------------------------------------------
| Handle Model Not Found (API Access)
|--------------------------------------------------------------------------
*/
API::error( function( Illuminate\Database\Eloquent\ModelNotFoundException $exception )
{
	// Treat as HTTP 404
	return handleHttpError( 404, ['source' => 'api'] );
});

/*
|--------------------------------------------------------------------------
| Handler Function for Common HTTP Status Codes
|--------------------------------------------------------------------------
*/
function handleHttpError($httpStatusCode, $options = [])
{
	switch ( $httpStatusCode )
	{
		case 400:
			$httpStatusName = 'Bad request';
			$httpDescription = 'The server cannot or will not process the request due to a client error.';
			$level = 'notice';
		break;
		case 401:
			$httpStatusName = 'Unauthorized';
			$httpDescription = 'The request has not been applied because it lacks valid authentication credentials for the target resource.';
			$level = 'notice';
		break;
		case 403:
			$httpStatusName = 'Forbidden';
			$httpDescription = 'The server understood the request but refuses to authorize it.';
			$level = 'notice';
		break;
		case 404:
			$httpStatusName = 'Not found';
			$httpDescription = 'The server did not find a current representation for the target resource.';
			$level = 'notice';
		break;
		case 405:
			$httpStatusName = 'Method not allowed';
			$httpDescription = 'The method received in the request is known by the server but not supported by the target resource.';
			$level = 'notice';
		break;
		case 422:
			$httpStatusName = 'Unprocessable entity';
			$httpDescription = 'The request was well-formed but was unable to be followed due to semantic errors.';
			$level = 'notice';
		break;
		case 500:
			$httpStatusName = 'Internal Server Error';
			$httpDescription = 'The server encountered an unexpected condition which prevented it from fulfilling the request.';
			$level = 'error';
		break;
		default:
			$httpStatusName = $httpStatusCode;
			$httpDescription = $httpStatusCode;
			$level = 'error';		
	}

	$description = ( isset( $options['description'] ) ) ? $options['description'] : $httpDescription;

	if ( ! isset($options['log']) OR $options['log'] == true )
	{
		Log::{$level}( $httpStatusCode . ' ' . $httpStatusName . ': ' . Request::fullUrl(),
			[
				'description'	=> $description,
				'url' 			=> Request::fullUrl(),
				'headers'		=> Request::header(),
				'ips'			=> Request::ips()
			]
		);
	}

	if ( ! isset($options['source']) OR $options['source'] == 'gui' )
	{
		return Response::view( 'errors.default',
			[
				'title' => $httpStatusCode . ' ' . $httpStatusName,
				'description' => $description,
			],
			$httpStatusCode
		);
	}
	elseif ( isset($options['source']) && $options['source'] == 'api' )
	{
		$response = Response::make(
			[
				'message' => $description,
			],
			$httpStatusCode
		);
		if ( $httpStatusCode == 401 ) $response->header('WWW-Authenticate', 'Lanager');
		return $response;
	}
}
