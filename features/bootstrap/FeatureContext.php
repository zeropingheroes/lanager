<?php

namespace Features\bootstrap;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\AfterStepScope;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Testwork\Tester\Result\TestResult;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Zeropingheroes\Lanager\Models\Achievement;
use Zeropingheroes\Lanager\Models\Attendee;
use Zeropingheroes\Lanager\Models\Event;
use Zeropingheroes\Lanager\Models\Guide;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\NavigationLink;
use Zeropingheroes\Lanager\Models\Slide;
use Zeropingheroes\Lanager\Models\User;
use Zeropingheroes\Lanager\Models\UserOAuthAccount;
use Zeropingheroes\Lanager\Models\Venue;

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
        User::factory()
            ->has(
                UserOAuthAccount::factory()->count(1),
                'accounts'
            )
            ->count(100)->create([
                'username' => $username,
            ]);

        // Currently not required as UserObserver::class assigns super admin role when first user created
        // $role = Role::where('name', 'super-admin')->first();
        // $user->roles()->attach($role->id, ['assigned_by' => $user->id]);
    }

    /**
     * @Given /^a user with username "([^"]*)" exists$/
     */
    public function aUserWithUsernameExists($username)
    {
        User::factory()
            ->has(
                UserOAuthAccount::factory()->count(1),
                'accounts'
            )
            ->count(100)->create([
                'username' => $username,
            ]);

        // Ensure user has no roles
        User::where('username', '=', $username)->first()->roles()->delete();
    }

    /**
     * @Given /^the following venues exist:$/
     */
    public function theFollowingVenuesExist(TableNode $venues)
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
     * @Given /^the following LANs exist:$/
     */
    public function theFollowingLANsExist(TableNode $lans)
    {
        foreach ($lans as $lan) {
            $lan['published'] = $lan['published'] == 'yes' ? 1 : 0;
            Lan::create(
                [
                    'name' => $lan['name'],
                    'description' => $lan['description'] ?? null,
                    'start' => Carbon::parse($lan['start']),
                    'end' => Carbon::parse($lan['end']),
                    'venue_id' => Venue::where('name', '=', $lan['venue'])->first()->id,
                    'published' => $lan['published'],
                ]
            );
        }
    }

    /**
     * @Given /^the following events exist:$/
     */
    public function theFollowingEventsExist(TableNode $events)
    {
        foreach ($events as $event) {
            $event['published'] = $event['published'] == 'yes' ? 1 : 0;
            Event::create(
                [
                    'name' => $event['name'],
                    'description' => $event['description'],
                    'start' => Carbon::parse($event['start']),
                    'end' => Carbon::parse($event['end']),
                    'signups_open' => isset($event['signups_open']) ? Carbon::parse($event['signups_open']) : null,
                    'signups_close' => isset($event['signups_close']) ? Carbon::parse($event['signups_close']) : null,
                    'lan_id' => Lan::where('name', '=', $event['lan'])->first()->id,
                    'published' => $event['published'],
                ]
            );
        }
    }

    /**
     * @Given /^"([^"]*)" is signed up to event "([^"]*)"$/
     */
    public function isSignedUpToEvent($username, $eventName)
    {
        $user = User::where('username', $username)->first();
        Event::where('name', $eventName)->first()->signups()->create(['user_id' => $user->id]);
    }

    /**
     * @Given /^the following guides exist:$/
     */
    public function theFollowingGuidesExist(TableNode $guides)
    {
        foreach ($guides as $guide) {
            $guide['published'] = $guide['published'] == 'yes' ? 1 : 0;
            Guide::create(
                [
                    'title' => $guide['title'],
                    'content' => $guide['content'],
                    'lan_id' => Lan::where('name', '=', $guide['lan'])->first()->id,
                    'published' => $guide['published'],
                ]
            );
        }
    }

    /**
     * @Given /^the following achievements exist:$/
     */
    public function theFollowingAchievementsExist(TableNode $achievements)
    {
        foreach ($achievements as $achievement) {
            Achievement::create(
                [
                    'name' => $achievement['name'],
                    'description' => $achievement['description'],
                    'lan_id' => Lan::where('name', '=', $achievement['lan'])->first()->id,
                ]
            );
        }
    }

    /**
     * @Given /^the user "([^"]*)" has attended the LAN "([^"]*)"$/
     */
    public function theUserHasAttendedTheLAN($username, $lanName)
    {
        Attendee::create(
            [
                'user_id' => User::where('username', $username)->first()->id,
                'lan_id' => Lan::where('name', $lanName)->first()->id,
            ]
        )->touch();
    }

    /**
     * @Given /^the following slides exist:$/
     */
    public function theFollowingSlidesExist(TableNode $slides)
    {
        foreach ($slides as $slide) {
            $slide['published'] = $slide['published'] == 'yes' ? 1 : 0;

            Slide::create(
                [
                    'name' => $slide['name'],
                    'content' => $slide['content'],
                    'position' => $slide['position'],
                    'duration' => $slide['duration'],
                    'published' => $slide['published'],
                    'lan_id' => Lan::where('name', '=', $slide['lan'])->first()->id,
                ]
            );
        }
    }

    /**
     * @Given /^the following navigation links exist:$/
     */
    public function theFollowingNavigationLinksExist(TableNode $navigationLinks)
    {
        foreach ($navigationLinks as $navigationLink) {
            NavigationLink::create(
                [
                    'title' => $navigationLink['title'],
                    'position' => $navigationLink['position'],
                    'url' => $navigationLink['url'],
                    'parent_id' => $navigationLink['parent_id'] ?? null,
                ]
            );
        }
    }
}
