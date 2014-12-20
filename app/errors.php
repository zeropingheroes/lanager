<?php
/*
|--------------------------------------------------------------------------
| Error Handlers
|--------------------------------------------------------------------------
*/
function handle404($message = '') {
	$message = empty($message) ? 'The requested resource was not found' : $message;
	return Response::view('errors.http', array('title' => '404 Not Found', 'code' => 404, 'error' => $message), 404);
}

/*
|--------------------------------------------------------------------------
| Uncaught exception
|--------------------------------------------------------------------------
*/
App::error(function(Exception $exception, $code)
{
	if( ! Config::get('app.debug') )
	{
		$message = 'An error was encountered';
		return Response::view('errors.http', array('title' => '500 Internal Server Error', 'code' => 500, 'error' => $message), 500);
	}
	Log::error($exception);
});

/*
|--------------------------------------------------------------------------
| HTTP Error Codes (Except 404)
|--------------------------------------------------------------------------
*/
App::error(function(Symfony\Component\HttpKernel\Exception\HttpException $exception, $code)
{
	$headers = $exception->getHeaders();

	switch ($code)
	{

		case 403:
			$defaultMessage = 'Insufficient privileges to perform this action';
			$viewTitle = '403 Forbidden';
		break;

		default:
			$defaultMessage = 'An error was encountered';
			$viewTitle = '500 Internal Server Error';
			$code = 500;
	}

	$message = $exception->getMessage() ?: $defaultMessage;
	
	return Response::view('errors.http', array('title' => $viewTitle, 'code' => $code, 'error' => $message), $code);
});

/*
|--------------------------------------------------------------------------
| HTTP 404
|--------------------------------------------------------------------------
*/
App::missing(function($exception)
{
	return handle404($exception->getMessage()); // always display pretty 404 page for non-existant routes
});

/*
|--------------------------------------------------------------------------
| Model Not Found
|--------------------------------------------------------------------------
*/
API::error(function(Illuminate\Database\Eloquent\ModelNotFoundException $exception)
{
    return Response::make(['message' => 'Not found'], 404);
});
App::error(function(Illuminate\Database\Eloquent\ModelNotFoundException $exception)
{
	if( ! Config::get('app.debug') ) return handle404(); // Only display pretty 404 page if we arent in debug mode
});

