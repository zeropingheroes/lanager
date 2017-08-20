<?php namespace Zeropingheroes\Lanager\Domain;

interface ServiceUserContract
{
    public function id();

    public function hasRole($role);

    public function isAuthenticated();

}