<?php

namespace Zeropingheroes\Lanager\Http\Middleware;

use App;
use Closure;
use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

class EncryptCookies extends Middleware
{
    /**
     * The names of the cookies that should not be encrypted.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];

    /**
     * @inheritDoc
     */
    public function handle($request, Closure $next)
    {
        // Skip encrypting cookies during testing
        if (App::environment() == 'testing') {
            return $next($request);
        }
        return parent::handle($request, $next);
    }
}
