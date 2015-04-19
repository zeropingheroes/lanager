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
	if( ! Config::get('app.debug') ) return handleHttpError( 500, ['log' => false] );
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
		case 401:
			$httpStatusName = 'Unauthorized';
			$httpDescription = 'Access is denied due to invalid credentials.';
			$level = 'notice';
		break;
		case 403:
			$httpStatusName = 'Forbidden';
			$httpDescription = 'Insufficient privileges to perform this action.';
			$level = 'notice';
		break;
		case 404:
			$httpStatusName = 'Not Found';
			$httpDescription = 'The requested resource was not found.';
			$level = 'notice';
		break;
		case 500:
			$httpStatusName = 'Internal Server Error';
			$httpDescription = 'The server encountered an unexpected condition which prevented it from fulfilling the request.';
			$level = 'error';
		break;
	}

	if( ! isset($options['log']) OR $options['log'] == true )
	{
		Log::{$level}( $httpStatusCode . ' ' . $httpStatusName . ': ' . Request::fullUrl(),
			[
				'url' 		=> Request::fullUrl(),
				'headers'	=> Request::header(),
				'ips'		=> Request::ips()
			]
		);
	}

	if( ! isset($options['source']) OR $options['source'] == 'gui' )
	{
		return Response::view( 'errors.http',
			[
				'title' => $httpStatusCode . ' ' . $httpStatusName,
				'httpDescription' => $httpDescription,
			],
			$httpStatusCode
		);
	}
	elseif( isset($options['source']) && $options['source'] == 'api' )
	{
		$response = Response::make(
			[
				'message' => $httpDescription,
			],
			$httpStatusCode
		);
		if( $httpStatusCode == 401 ) $response->header('WWW-Authenticate', 'Lanager');
		return $response;
	}
}
