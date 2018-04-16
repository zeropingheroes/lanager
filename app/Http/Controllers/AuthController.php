<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Exception;
use InvalidArgumentException;
use Socialite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Zeropingheroes\Lanager\Services\SteamUserImportService;
use Zeropingheroes\Lanager\UserOAuthAccount;

/**
 * Class AuthController
 * @package Zeropingheroes\Lanager\Http\Controllers\Auth
 */
class AuthController extends Controller
{

    /**
     * Show the login page.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('pages.auth.login');
    }

    /**
     * Redirect the user to the external authentication provider.
     *
     * @param $OAuthProvider string
     * @return Response
     * @throws InvalidArgumentException
     */
    public function redirectToProvider($OAuthProvider)
    {
        if ($OAuthProvider == 'steam') {
            return Socialite::with('steam')->redirect();
        }
        throw new InvalidArgumentException(__('phrase.provider-not-supported', ['provider' => $OAuthProvider]));

    }

    /**
     * Obtain the user information from the external authentication provider.
     *
     * @param $OAuthProvider
     * @return Response
     * @throws \Throwable
     */
    public function handleProviderCallback($OAuthProvider)
    {
        if ($OAuthProvider == 'steam') {
            $OAuthUser = Socialite::with('steam')->user();

            $service = new SteamUserImportService($OAuthUser->id);
            $service->import();

            if (in_array($OAuthUser->id, $service->getimported())) {
                $user = UserOAuthAccount::where('provider_id', $OAuthUser->id)
                    ->firstOrFail()
                    ->user;

                Auth::login($user, true);

                return redirect()->intended(route('users.show', ['id' => $user->id]));
            }

            throw new Exception($service->errors()->first());

        }

        throw new InvalidArgumentException(__('phrase.provider-not-supported', ['provider' => $OAuthProvider]));
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect('/');
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }
}
