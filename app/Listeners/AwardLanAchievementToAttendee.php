<?php

namespace Zeropingheroes\Lanager\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Zeropingheroes\Lanager\Lan;
use Zeropingheroes\Lanager\UserAchievement;
use Zeropingheroes\Lanager\WhitelistedIpRange;
use Symfony\Component\HttpFoundation\IpUtils;

class AwardLanAchievementToAttendee
{
    protected $request;

    /**
     * Create the event listener.
     *
     * @return void
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
        $lanHappeningNow = Lan::where('start', '<', now())
            ->where('end', '>', now())->first();

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

        if($isAtLan && $lanHappeningNow && $lanHappeningNow->has('achievement'))
        {
            $userHasAchievement = $login->user->whereHas('achievements', function ($query) use ($lanHappeningNow) {
                $query->where('id', $lanHappeningNow->achievement_id);
            })->first();

            if(!$userHasAchievement)
                UserAchievement::create([
                    'user_id' => $login->user->id,
                    'achievement_id' => $lanHappeningNow->achievement_id,
                    'lan_id' => $lanHappeningNow->id
                ]);
        }
    }
}
