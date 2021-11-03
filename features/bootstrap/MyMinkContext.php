<?php

namespace Features\bootstrap;

use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\MinkContext;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session as LaravelSession;
use Zeropingheroes\Lanager\User;

/**
 * Defines application features from the specific context.
 */
class MyMinkContext extends MinkContext implements Context
{
    /**
     * @Given I am logged in as :username
     * @When  I log in as :username
     */
    public function logInAs($username)
    {
        // Destroy the previous session
        if (LaravelSession::isStarted()) {
            LaravelSession::regenerate(true);
        } else {
            LaravelSession::start();
        }

        $user = User::where('username', '=', $username)->firstOrFail();

        Auth::login($user);

        LaravelSession::save();

        $this->getSession()->setCookie(LaravelSession::getName(), $user->sessions()->first()->id);
    }

    /**
     * @When I log out
     */
    public function iLogOut()
    {
        // Log out page uses HTTP DELETE so GET /logout does not work
        // $this->visit('logout');
        // if ($this->getSession()->getStatusCode() != 200) {
        //     throw new \Exception('HTTP ' . $this->getSession()->getStatusCode());
        // }

        // Workaround: unset the cookie on the browser
        $this->getSession()->setCookie(LaravelSession::getName(), null);
    }
}
