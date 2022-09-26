<?php

namespace Tests;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Laravel\Dusk\Browser;
use Laravel\Dusk\TestCase as BaseTestCase;
use Zeropingheroes\Lanager\Models\Role;
use Zeropingheroes\Lanager\Models\User;
use Zeropingheroes\Lanager\Models\UserOAuthAccount;

abstract class DuskTestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed');
        $this->artisan('lanager:import-steam-apps-csv');

        Browser::$storeScreenshotsAt = storage_path('logs/dusk/screenshots');
        Browser::$storeConsoleLogAt = storage_path('logs/dusk/console');
        Browser::$storeSourceAt = storage_path('logs/dusk/source');
    }

    /**
     * Prepare for Dusk test execution.
     *
     * @beforeClass
     * @return void
     */
    public static function prepare()
    {
        // if (! static::runningInSail()) {
        //     static::startChromeDriver();
        // }
    }

    /**
     * Create the RemoteWebDriver instance.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    protected function driver()
    {
        $options = (new ChromeOptions())->addArguments(
            collect([
                $this->shouldStartMaximized() ? '--start-maximized' : '--window-size=1920,1080',
            ])->unless($this->hasHeadlessDisabled(), function ($items) {
                return $items->merge([
                    '--disable-gpu',
                    '--headless',
                    '--remote-debugging-port=9222'
                ]);
            })->all()
        );

        return RemoteWebDriver::create(
            env('DUSK_DRIVER_URL'),
            DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY,
                $options
            )
        );
    }

    /**
     * Determine whether the Dusk command has disabled headless mode.
     *
     * @return bool
     */
    protected function hasHeadlessDisabled()
    {
        return isset($_SERVER['DUSK_HEADLESS_DISABLED']) ||
            isset($_ENV['DUSK_HEADLESS_DISABLED']);
    }

    /**
     * Determine if the browser window should start maximized.
     *
     * @return bool
     */
    protected function shouldStartMaximized()
    {
        return isset($_SERVER['DUSK_START_MAXIMIZED']) ||
            isset($_ENV['DUSK_START_MAXIMIZED']);
    }

    protected function createSuperAdmin(): User
    {
        $user = User::factory()
            ->has(
                UserOAuthAccount::factory()->count(1),
                'accounts'
            )
            ->create();

        $role = Role::where('name', 'super-admin')->first();
        $user->roles()->attach($role->id, ['assigned_by' => $user->id]);

        return $user;
    }
}
