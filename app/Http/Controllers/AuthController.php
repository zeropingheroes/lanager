<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use InvalidArgumentException;
use Laravel\Socialite\Facades\Socialite;
use Throwable;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\User;
use Zeropingheroes\Lanager\Models\UserOAuthAccount;
use Zeropingheroes\Lanager\Services\UpdateSteamUsersService;

class AuthController extends Controller
{
    /**
     * Show the login page.
     */
    public function showLoginForm(): ViewContract
    {
        return View::make('pages.auth.login');
    }

    /**
     * Redirect the user to the external authentication provider.
     */
    public function redirectToProvider(string $OAuthProvider): RedirectResponse
    {
        if ($OAuthProvider === 'steam') {
            return Socialite::with('steam')->redirect();
        }

        $message = trans('phrase.provider-not-supported', ['provider' => $OAuthProvider]);
        Log::error($message);
        throw new InvalidArgumentException($message);
    }

    /**
     * Obtain the user information from the external authentication provider.
     *
     * @throws Throwable
     */
    public function handleProviderCallback($OAuthProvider): RedirectResponse
    {
        if ($OAuthProvider == 'steam') {
            $OAuthUser = Socialite::with('steam')->user();

            $updateSteamUsersService = new UpdateSteamUsersService([$OAuthUser->id]);
            $updateSteamUsersService->update();

            // Check if the user wasn't updated, or if there are errors
            if (
                ! array_key_exists($OAuthUser->id, $updateSteamUsersService->getUpdated())
                || $updateSteamUsersService->errors()->isNotEmpty()
            ) {
                Log::error($updateSteamUsersService->errors()->first());
                Session::flash('error', $updateSteamUsersService->errors()->first());

                return redirect()->route('login');
            }

            // Get the newly updated user
            $userOAuthAccount = UserOAuthAccount::where('provider_id', $OAuthUser->id)->firstOrFail();
            $user = User::findOrFail($userOAuthAccount->user_id);

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
     */
    public function logout(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $this->guard()->logout();
        $request->session()->invalidate();
        Log::info(trans('phrase.user-successfully-logged-out', ['username' => $user->username]));

        return redirect()->to('/');
    }

    /**
     * Get the guard to be used during authentication.
     */
    protected function guard(): StatefulGuard
    {
        return Auth::guard();
    }
}
