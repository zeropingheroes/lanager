<?php

namespace Zeropingheroes\Lanager\Services;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\MessageBag;
use Steam;
use Zeropingheroes\Lanager\UserOAuthAccount;

class SteamUserAppImportService
{
    /**
     * LANager users to be imported
     *
     * @var Collection
     */
    protected $users = [];

    /**
     * Users whose apps were successfully imported
     *
     * @var Collection
     */
    protected $imported = [];

    /**
     * Users whose apps were not imported due to errors
     *
     * @var Collection
     */
    protected $failed = [];

    /**
     * Errors
     *
     * @var \Illuminate\Support\MessageBag
     */
    protected $errors;

    /**
     * @param Collection $users
     * @throws Exception
     * @internal param array $users
     */
    public function __construct(Collection $users)
    {
        if ($users->isEmpty()) {
            throw new Exception(__('phrase.no-steam-users-to-import'));
        }

        $this->users = $users;
        $this->errors = new MessageBag();
        $this->imported = new Collection();
        $this->failed = new Collection();
    }

    /**
     * Import errors
     *
     * @return MessageBag
     */
    public function errors(): MessageBag
    {
        return $this->errors;
    }

    /**
     * @return Collection
     */
    public function getImported(): Collection
    {
        return $this->imported;
    }

    /**
     * @return Collection
     */
    public function getFailed(): Collection
    {
        return $this->failed;
    }

    /**
     * Import Steam users apps
     * @return void
     * @throws \Throwable
     */
    public function import(): void
    {
        $steamAccounts = UserOAuthAccount::where('provider', 'steam')
            ->whereIn('user_id', $this->users->pluck('id'))->get();

        // Import games for each user in turn
        foreach ($steamAccounts as $steamAccount) {
            try {
                $apps = Steam::player($steamAccount->provider_id)->GetOwnedGames();

                $appsVisible = (count($apps) != 0);

                $steamAccount->user->SteamMetadata()->updateOrCreate(
                    [],
                    [
                        'apps_visible' => $appsVisible,
                        'apps_updated_at' => now()
                    ]
                );

                foreach ($apps as $app) {
                    $steamAccount->user->SteamApps()
                        ->updateOrCreate(
                            ['steam_app_id' => $app->appId],
                            [
                                'playtime_two_weeks' => $app->playtimeTwoWeeks,
                                'playtime_forever' => $app->playtimeForever
                            ]
                        );
                }

                // Add the user to the imported collection
                $this->imported->push($steamAccount->user);

            } catch (Exception $e) {
                $this->errors->add(
                    $steamAccount->provider_id,
                    __(
                        'phrase.unable-to-import-apps-for-user',
                        ['username' => $steamAccount->user->username, 'error' => $e->getMessage()]
                    )
                );
                // Add the user to the failed collection
                $this->failed->push($steamAccount->user);
            }
        }
    }
}