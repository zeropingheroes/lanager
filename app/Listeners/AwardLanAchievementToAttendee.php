<?php

namespace Zeropingheroes\Lanager\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\IpUtils;
use Zeropingheroes\Lanager\Models\AllowedIpRange;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\UserAchievement;

class AwardLanAchievementToAttendee
{
    protected Request $request;

    /**
     * Create the event listener.
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Handle the event.
     */
    public function handle(Login $login): void
    {
        $lanHappeningNow = Lan::happeningNow()->first();

        if (! $lanHappeningNow) {
            return;
        }
        $isAtLan = false;

        foreach (AllowedIpRange::pluck('ip_range') as $ipRange) {
            if (IpUtils::checkIp($this->request->ip(), $ipRange)) {
                $isAtLan = true;
                break;
            }
        }

        if ($isAtLan && $lanHappeningNow->attendanceAchievement) {
            UserAchievement::firstOrCreate(
                [
                    'user_id' => $login->user->id,
                    'achievement_id' => $lanHappeningNow->attendanceAchievement->id,
                    'lan_id' => $lanHappeningNow->id,
                ]
            );
        }
    }
}
