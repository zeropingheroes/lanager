<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Mink\Driver\GoutteDriver;
use Behat\Mink\Session as MinkSession;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session as LaravelSession;
use Tests\TestCase;
use Zeropingheroes\Lanager\Console\Kernel;
use Zeropingheroes\Lanager\User;
use Zeropingheroes\Lanager\UserOAuthAccount;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends TestCase implements Context
{
    use DatabaseMigrations;

    /**
     * @var Session
     */
    private $session;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        parent::setUp();
        $driver = new GoutteDriver();
        $this->session = new MinkSession($driver);
        $this->session->start();
    }

    /** @BeforeScenario */
    public function before(BeforeScenarioScope $scope)
    {
        $this->artisan('db:seed');
        $this->app[Kernel::class]->setArtisan(null);
    }

    /**
     * @Given an admin with username :username exists
     */
    public function anAdminWithUsernameExists($username)
    {
        $user = factory(User::class)->create(
            [
                'username' => $username,
            ]
        )->each(
            function ($user) {
                $user->accounts()->save(factory(UserOAuthAccount::class)->make());
            }
        );

        // Currently not required as UserObserver::class assigns super admin role when first user created
        // $role = Role::where('name', 'super-admin')->first();
        // $user->roles()->attach($role->id, ['assigned_by' => $user->id]);
    }

    /**
     * @Given I am logged in as :username
     */
    public function iAmLoggedInAs($username)
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

        $this->session->setCookie(LaravelSession::getName(), $user->sessions()->first()->id);
    }

    /**
     * @When I type :value into the :field field
     */
    public function iTypeIntoThe($value, $field)
    {
        $page = $this->session->getPage();
        $page->fillField($field, $value);
    }

    /**
     * @When I log out
     */
    public function iLogOut()
    {
        $this->session->visit(env('APP_URL') . 'logout');
        if ($this->session->getStatusCode() != 200) {
            throw new \Exception('HTTP ' . $this->session->getStatusCode());
        }
    }

    /**
     * @When I visit :path
     */
    public function iVisit($path)
    {
        $this->session->visit(env('APP_URL') . $path);
    }

    /**
     * @When I submit the form
     */
    public function iSubmitTheForm()
    {
        $page = $this->session->getPage();
        $page->find('css', 'body > main.container > form')->submit();
    }

    /**
     * @Then I should see the text :text
     */
    public function iShouldSeeTheText($text)
    {
        $cell = $this->session->getPage()->find(
            'css',
            sprintf('body main table tbody tr td:contains("%s")', $text)
        );
        if (null === $cell) {
            throw new \Exception('The text is not found');
        }
    }


}
