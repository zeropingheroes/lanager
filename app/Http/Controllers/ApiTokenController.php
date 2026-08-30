<?php

declare(strict_types=1);

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Zeropingheroes\Lanager\Requests\StoreApiTokenRequest;

class ApiTokenController extends Controller
{
    /**
     * Display the current user's personal access tokens, with an inline creation form.
     */
    public function index(Request $httpRequest): ViewContract
    {
        $tokens = $httpRequest->user()->tokens()->orderByDesc('created_at')->get();

        return View::make('pages.api-tokens.index')
            ->with('tokens', $tokens);
    }

    /**
     * Create a new personal access token for the current user.
     */
    public function store(Request $httpRequest): RedirectResponse
    {
        $input = ['name' => $httpRequest->input('name')];

        $storeApiTokenRequest = new StoreApiTokenRequest($input);

        if ($storeApiTokenRequest->invalid()) {
            Session::flash('error', $storeApiTokenRequest->errors());

            return redirect()->back()->withInput();
        }

        $token = $httpRequest->user()->createToken($input['name']);

        Session::flash('success', trans('phrase.api-token-created'));
        Session::flash('new_api_token', $token->plainTextToken);

        return redirect()->route('api-tokens.index');
    }

    /**
     * Revoke one of the current user's personal access tokens.
     */
    public function destroy(Request $httpRequest, int $apiToken): RedirectResponse
    {
        $token = $httpRequest->user()->tokens()->find($apiToken);

        if ($token === null) {
            abort(404);
        }

        $tokenName = $token->name;
        $token->delete();

        Session::flash(
            'success',
            trans('phrase.item-name-deleted', ['item' => trans('title.api-token'), 'name' => $tokenName])
        );

        return redirect()->route('api-tokens.index');
    }
}
