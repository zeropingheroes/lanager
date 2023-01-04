<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Zeropingheroes\Lanager\Models\Lan;

class CurrentLanController extends Controller
{
    /**
     * @var Lan
     */
    protected $lan;

    /**
     * Determine the current LAN.
     */
    public function __construct()
    {
        // LAN happening now, or closest future LAN, or most recently ended past LAN
        $this->lan = Lan::happeningNow()->where('published', 1)->first()
            ?? Lan::future()->where('published', 1)->orderBy('start', 'asc')->first()
            ?? Lan::past()->where('published', 1)->orderBy('end', 'desc')->first();

        if (!$this->lan) {
            redirect()->route('lans.index')->send();
        }
    }

    /**
     * Redirect to current LAN's page.
     *
     * @return RedirectResponse
     */
    public function show(Request $request)
    {
        return redirect()->route('lans.show', $this->lan)->with($request->session()->all());
    }

    /**
     * Redirect to current LAN's guides index.
     *
     * @return RedirectResponse
     */
    public function guides(Request $request)
    {
        return redirect()->route('lans.guides.index', $this->lan)->with($request->session()->all());
    }

    /**
     * Redirect to current LAN's events index.
     *
     * @return RedirectResponse
     */
    public function events(Request $request)
    {
        return redirect()->route('lans.events.index', $this->lan)->with($request->session()->all());
    }

    /**
     * Redirect to current LAN's events schedule.
     *
     * @return RedirectResponse
     */
    public function schedule(Request $request)
    {
        return redirect()->route('lans.events.index', ['lan' => $this->lan, 'schedule'])->with(
            $request->session()->all()
        );
    }

    /**
     * Redirect to current LAN's attendees index.
     *
     * @return RedirectResponse
     */
    public function users(Request $request)
    {
        return redirect()->route('lans.attendees.index', $this->lan)->with($request->session()->all());
    }

    /**
     * Redirect to current LAN's awarded achievements.
     *
     * @return RedirectResponse
     */
    public function userAchievements(Request $request)
    {
        return redirect()->route('lans.user-achievements.index', $this->lan)->with($request->session()->all());
    }
}
