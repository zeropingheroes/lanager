<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Socialite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
     * @return Response
     * @throws InvalidArgumentException
     */
    public function redirectToProvider($OAuthProvider)
    {
        if ($OAuthProvider == 'steam') {
            return Socialite::with('steam')->redirect();
        }
        $message = __('phrase.provider-not-supported', ['provider' => $OAuthProvider]);
        Log::error($message);
        throw new InvalidArgumentException($message);

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

            $service = new UpdateSteamUsersService($OAuthUser->id);
            $service->update();

            // Check if the user wasn't updated, or if there are errors
            if (!array_key_exists($OAuthUser->id, $service->getUpdated()) ||
                $service->errors()->isNotEmpty()) {
                Log::error($service->errors()->first());

                return redirect()
                    ->route('login')
                    ->withError($service->errors()->first());
            }

            // Get the newly updated user
            $user = UserOAuthAccount::where('provider_id', $OAuthUser->id)
                ->firstOrFail()
                ->user;

            // Log them in
            Auth::login($user, true);
            Log::info(__('phrase.user-successfully-logged-in', ['username' => $user->username]));

            return redirect()->intended(route('users.show', ['id' => $user->id]));

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
