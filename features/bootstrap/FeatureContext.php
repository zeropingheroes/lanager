<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Illuminate\Foundation\Testing\DatabaseMigrations;
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

}
