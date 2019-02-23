<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Zeropingheroes\Lanager\AuthProviders\AuthHelper;
use Zeropingheroes\Lanager\Services\UpdateSteamUsersService;
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
     * @return \Illuminate\Http\RedirectResponse
     * @throws InvalidArgumentException
     */
    public function redirectToProvider($OAuthProvider)
    {
        return AuthHelper::provider($OAuthProvider)->to();

    }

    /**
     * Obtain the user information from the external authentication provider.
     *
     * @param $OAuthProvider
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function handleProviderCallback($OAuthProvider)
    {
        $provider = AuthHelper::provider($OAuthProvider);
        $OAuthUser = $provider->from();

        // Get the newly updated user
        $user = UserOAuthAccount::where('provider_id', $OAuthUser->id)
            ->firstOrFail()
            ->user;

        if (Auth::user()) {
            // Account connecting, rather than logging in.
            Log::info(__('phrase.account-connected', ['provider' => $provider->getName()]));
        } else {
            // Log them in
            Auth::login($user, true);
            Log::info(__('phrase.user-successfully-logged-in', ['username' => $user->username]));
        }

        return redirect()->intended(route('users.show', ['id' => $user->id]));
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        $this->guard()->logout();
        $request->session()->invalidate();
        Log::info(__('phrase.user-successfully-logged-out', ['username' => $user->username]));
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
