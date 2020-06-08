<?php

namespace Zeropingheroes\Lanager\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\IpUtils;
use Zeropingheroes\Lanager\Lan;
use Zeropingheroes\Lanager\UserAchievement;
use Zeropingheroes\Lanager\WhitelistedIpRange;

class AwardLanAchievementToAttendee
{
    protected $request;

    /**
     * Create the event listener.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Handle the event.
     *
     * @param Login $login
     * @return void
     */
    public function handle(Login $login)
    {
        $lanHappeningNow = Lan::happeningNow()->first();

        if(!$lanHappeningNow) {
            return;
        }
        $isAtLan = false;

        foreach(WhitelistedIpRange::pluck('ip_range') as $ipRange)
        {
            if (IpUtils::checkIp($this->request->ip(), $ipRange))
            {
                $isAtLan = true;
                break;
            }
        }

        if($isAtLan && $lanHappeningNow && $lanHappeningNow->attendanceAchievement)
        {
            UserAchievement::firstOrCreate([
                'user_id' => $login->user->id,
                'achievement_id' => $lanHappeningNow->attendanceAchievement->id,
                'lan_id' => $lanHappeningNow->id
            ]);
        }
    }
}
