<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Zeropingheroes\Lanager\Lan;

class CurrentLanController extends Controller
{
    /**
     * @var \Zeropingheroes\Lanager\Lan
     */
    protected $lan;

    /**
     * Determine the current LAN
     */
    public function __construct()
    {
        $this->lan = Lan::happeningNow()->where('published', 1)->first()                       // LAN happening now
                  ?? Lan::future()->where('published', 1)->orderBy('start', 'asc')->first()    // Closest future LAN
                  ?? Lan::past()->where('published', 1)->orderBy('end', 'desc')->first();      // Most recently ended past LAN

        // If there are no LANs, go to the LAN index page
        if (!$this->lan) {
            redirect()->route('lans.index')->send();
        }
    }

    /**
     * Redirect to current LAN's page
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show()
    {
        return redirect()->route('lans.show', $this->lan);
    }

    /**
     * Redirect to current LAN's guides index
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function guides()
    {
        return redirect()->route('lans.guides.index', $this->lan);
    }

    /**
     * Redirect to current LAN's events index
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function events()
    {
        return redirect()->route('lans.events.index', $this->lan);
    }

    /**
     * Redirect to current LAN's events schedule
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function schedule()
    {
        return redirect()->route('lans.events.index', ['lan' => $this->lan, 'schedule']);
    }

    /**
     * Redirect to current LAN's attendees index
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function users()
    {
        return redirect()->route('lans.attendees.index', $this->lan);
    }

    /**
     * Redirect to current LAN's awarded achievements
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function userAchievements()
    {
        return redirect()->route('lans.user-achievements.index', $this->lan);
    }
}
