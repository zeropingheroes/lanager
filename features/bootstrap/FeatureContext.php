<?php

namespace Features\bootstrap;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\AfterStepScope;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Testwork\Tester\Result\TestResult;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Zeropingheroes\Lanager\Event;
use Zeropingheroes\Lanager\Lan;
use Zeropingheroes\Lanager\User;
use Zeropingheroes\Lanager\UserOAuthAccount;
use Zeropingheroes\Lanager\Venue;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends TestCase implements Context
{

    /** @var MinkContext */
    private MinkContext $minkContext;

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

    /**
     * @BeforeScenario
     * http://behat.readthedocs.org/en/v3.0/cookbooks/context_communication.html
     */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        $environment = $scope->getEnvironment();
        $this->minkContext = $environment->getContext('Features\bootstrap\MyMinkContext');
    }

    /**
     * @BeforeScenario
     */
    public function deleteDatabaseData()
    {
        DB::table('achievements')->delete();
        DB::table('allowed_ip_ranges')->delete();
        DB::table('event_signups')->delete();
        DB::table('events')->delete();
        DB::table('guides')->delete();
        DB::table('lan_attendees')->delete();
        DB::table('lan_game_votes')->delete();
        DB::table('lan_games')->delete();
        DB::table('lans')->delete();
        DB::table('navigation_links')->delete();
        DB::table('role_assignments')->delete();
        DB::table('sessions')->delete();
        DB::table('slides')->delete();
        DB::table('steam_user_app_sessions')->delete();
        DB::table('steam_user_apps')->delete();
        DB::table('steam_user_metadata')->delete();
        DB::table('user_achievements')->delete();
        DB::table('user_oauth_accounts')->delete();
        DB::table('users')->delete();
        DB::table('venues')->delete();
    }

    /**
     * @AfterStep
     */
    public function outputFailureDetails(AfterStepScope $scope)
    {
        if (TestResult::FAILED === $scope->getTestResult()->getResultCode()) {
            print 'URL: ' . $this->minkContext->getSession()->getCurrentUrl() . "\n";
            print 'Content: ' . "\n" . $this->minkContext->getSession()->getPage()->getContent() . "\n";
        }
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
     * @Given /^a user with username "([^"]*)" exists$/
     */
    public function aUserWithUsernameExists($username)
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

        // Ensure user has no roles
        User::where('username', '=', $username)->first()->roles()->delete();
    }

    /**
     * @Given /^the following venue exists:$/
     */
    public function theFollowingVenueExists(TableNode $venues)
    {
        foreach ($venues as $venue) {
            Venue::create(
                [
                    'name' => $venue['name'],
                    'street_address' => $venue['street_address'],
                    'description' => $venue['description'],
                ]
            );
        }
    }

    /**
     * @Given /^the following LAN exists:$/
     */
    public function theFollowingLANExists(TableNode $lan)
    {
        foreach ($lan as $lan) {
            $lan['published'] = $lan['published'] == 'yes' ? 1 : 0;
            Lan::create(
                [
                    'name' => $lan['name'],
                    'description' => $lan['description'],
                    'start' => $lan['start'],
                    'end' => $lan['end'],
                    'venue_id' => Venue::where('name', '=', $lan['venue'])->first()->id,
                    'published' => $lan['published'],
                ]
            );
        }
    }

    /**
     * @Given /^the following event exists:$/
     */
    public function theFollowingEventExists(TableNode $event)
    {
        foreach ($event as $event) {
            $event['published'] = $event['published'] == 'yes' ? 1 : 0;
            Event::create(
                [
                    'name' => $event['name'],
                    'description' => $event['description'],
                    'start' => $event['start'],
                    'end' => $event['end'],
                    'signups_open' => $event['signups_open'] ?? $event['signups_open'] ?? null,
                    'signups_close' => $event['signups_close'] ?? $event['signups_close'] ?? null,
                    'lan_id' => Lan::where('name', '=', $event['lan'])->first()->id,
                    'published' => $event['published'],
                ]
            );
        }
    }

    /**
     * @Given the LAN :lan is happening all day today at :venue on :streetAddress
     */
    public function theLanIsHappeningAllDayTodayAtOn($lan, $venue, $streetAddress)
    {
        throw new PendingException();
    }
}
