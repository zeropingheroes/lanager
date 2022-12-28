<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Auth;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use InvalidArgumentException;
use Log;
use Session;
use Socialite;
use Throwable;
use View;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\UserOAuthAccount;
use Zeropingheroes\Lanager\Services\UpdateSteamUsersService;

/**
 * Class AuthController.
 */
class AuthController extends Controller
{
    /**
     * Show the login page.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function showLoginForm()
    {
        return View::make('pages.auth.login');
    }

    /**
     * Redirect the user to the external authentication provider.
     *
     * @param  $OAuthProvider string
     * @return RedirectResponse
     */
    public function redirectToProvider($OAuthProvider)
    {
        if ($OAuthProvider == 'steam') {
            return Socialite::with('steam')->redirect();
        }
        $message = trans('phrase.provider-not-supported', ['provider' => $OAuthProvider]);
        Log::error($message);
        throw new InvalidArgumentException($message);
    }

    /**
     * Obtain the user information from the external authentication provider.
     *
     * @param  $OAuthProvider
     * @return RedirectResponse
     * @throws Throwable
     */
    public function handleProviderCallback($OAuthProvider)
    {
        if ($OAuthProvider == 'steam') {
            $OAuthUser = Socialite::with('steam')->user();

            $service = new UpdateSteamUsersService($OAuthUser->id);
            $service->update();

            // Check if the user wasn't updated, or if there are errors
            if (
                ! array_key_exists($OAuthUser->id, $service->getUpdated())
                || $service->errors()->isNotEmpty()
            ) {
                Log::error($service->errors()->first());
                Session::flash('error', $service->errors()->first());

                return redirect()->route('login');
            }

            // Get the newly updated user
            $user = UserOAuthAccount::where('provider_id', $OAuthUser->id)
                ->firstOrFail()
                ->user;

            // Log them in
            Auth::login($user, true);
            Log::info(trans('phrase.user-successfully-logged-in', ['username' => $user->username]));

            // Redirect the user:
            // - to where they wanted to go (if given) OR
            // - the LAN happening now (if exists) OR
            // - the nearest future LAN (if exists) OR
            // - the user's profile
            $lan = Lan::happeningNow()->first() ?? Lan::future()->orderBy('start', 'asc')->first();
            if ($lan) {
                $route = route('lans.events.index', ['lan' => $lan]);
            } else {
                $route = route('users.show', ['user' => $user]);
            }

            return redirect()->intended($route);
        }

        throw new InvalidArgumentException(trans('phrase.provider-not-supported', ['provider' => $OAuthProvider]));
    }

    /**
     * Log the user out of the application.
     *
     * @param  Request $request
     * @return RedirectResponse
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        $this->guard()->logout();
        $request->session()->invalidate();
        Log::info(trans('phrase.user-successfully-logged-out', ['username' => $user->username]));

        return redirect()->to('/');
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }
}
