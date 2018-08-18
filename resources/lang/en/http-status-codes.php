<?php

return [

    /*
    |--------------------------------------------------------------------------
    | HTTP Status Codes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used by the error pages to display
    | the correct error text corresponding to the status code.
    |
    | All descriptions have been taken from RFC 7231:
    | https://tools.ietf.org/html/rfc7231
    */

    '400-title' => 'Bad request',
    '400-description' => 'The server cannot or will not process the request due to a client error.',

    '401-title' => 'Unauthorized',
    '401-description' => 'The request has not been applied because it lacks valid authentication credentials for the target resource.',

    '403-title' => 'Forbidden',
    '403-description' => 'The server understood the request but refuses to authorize it.',

    '404-title' => 'Not found',
    '404-description' => 'The server did not find a current representation for the target resource.',

    '405-title' => 'Method not allowed',
    '405-description' => 'The method received in the request is known by the server but not supported by the target resource.',

    '422-title' => 'Unprocessable entity',
    '422-description' => 'The request was well-formed but was unable to be followed due to semantic errors.',

    '500-title' => 'Internal Server Error',
    '500-description' => 'The server encountered an unexpected condition which prevented it from fulfilling the request.',

];
