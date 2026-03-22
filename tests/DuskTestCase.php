<?php

namespace Tests;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\Browser;
use Laravel\Dusk\TestCase as BaseTestCase;
use Zeropingheroes\Lanager\Models\Role;
use Zeropingheroes\Lanager\Models\User;
use Zeropingheroes\Lanager\Models\UserOAuthAccount;

abstract class DuskTestCase extends BaseTestCase
{
    use DatabaseTruncation;

    protected array $exceptTables = ['steam_apps'];

    protected bool $seed = true;

    protected function setUp(): void
    {
        parent::setUp();

        restore_error_handler();

        Browser::$storeScreenshotsAt = storage_path('logs/dusk/screenshots');
        Browser::$storeConsoleLogAt = storage_path('logs/dusk/console');
        Browser::$storeSourceAt = storage_path('logs/dusk/source');
    }

    protected function tearDown(): void
    {
        $this->browse(function (Browser $browser): void {
            $sourceFilename = sprintf('%s-source', class_basename($this));
            $browser->storeSource($sourceFilename);
        });

        parent::tearDown();
    }

    /**
     * Prepare for Dusk test execution.
     *
     * @beforeClass
     */
    public static function prepare(): void
    {
        // if (! static::runningInSail()) {
        //     static::startChromeDriver();
        // }
    }

    /**
     * Create the RemoteWebDriver instance.
     */
    protected function driver(): RemoteWebDriver
    {
        $options = (new ChromeOptions)->addArguments(
            collect([
                $this->shouldStartMaximized() ? '--start-maximized' : '--window-size=1920,1080',
            ])->unless($this->hasHeadlessDisabled(), function ($items) {
                return $items->merge([
                    '--disable-gpu',
                    '--headless',
                    '--remote-debugging-port=9222',
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
     */
    protected function hasHeadlessDisabled(): bool
    {
        return isset($_SERVER['DUSK_HEADLESS_DISABLED']) ||
            isset($_ENV['DUSK_HEADLESS_DISABLED']);
    }

    /**
     * Determine if the browser window should start maximized.
     */
    protected function shouldStartMaximized(): bool
    {
        return isset($_SERVER['DUSK_START_MAXIMIZED']) ||
            isset($_ENV['DUSK_START_MAXIMIZED']);
    }

    /**
     * Create a super admin for use in a test
     */
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
