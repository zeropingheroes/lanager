<?php namespace Zeropingheroes\Lanager\Domain\Applications\SteamApplications;

interface SteamApplicationContract
{

    /**
     * Get all Steam Applications
     *
     * @return object SteamApplication|null
     */
    public function getApplicationList();

}