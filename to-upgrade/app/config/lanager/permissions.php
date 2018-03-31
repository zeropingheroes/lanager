<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Initialisation
    |--------------------------------------------------------------------------
    |
    | Be careful editing here - you could open up the system to serious abuse!
    | The order in which the permissions are set is important
    | All resources are plural
    |
    */
    'initialise' => function ($authority) {
        // Alias all REST verbs to their CRUD counterparts
        $authority->addAlias('create', ['create', 'store']);
        $authority->addAlias('read', ['read', 'index', 'show']);
        $authority->addAlias('update', ['update', 'edit']);
        $authority->addAlias('delete', ['delete', 'destroy']);

        // Create "do everything" alias
        $authority->addAlias('manage', ['create', 'store', 'read', 'index', 'show', 'update', 'edit', 'delete', 'destroy']);

        /*
        |--------------------------------------------------------------------------
        | Permissions for users who have not logged in
        |--------------------------------------------------------------------------
        */
        $authority->allow('read', 'achievements');
        $authority->allow('read', 'events');
        $authority->allow('read', 'event-types');
        $authority->allow('read', 'events.signups');
        $authority->allow('read', 'lans');
        $authority->allow('read', 'pages');
        $authority->allow('read', 'roles');
        $authority->allow('read', 'shouts');
        $authority->allow('read', 'usage');
        $authority->allow('read', 'users');
        $authority->allow('read', 'user-achievements');
        $authority->allow('read', 'user-roles');
        $authority->allow('read', 'application-usage');
        $authority->allow('read', 'dashboard');
        $authority->allow('read', 'api');
        $authority->allow('create', 'sessions');
        $authority->allow('delete', 'sessions');

        // Check if a user is logged in
        $self = $authority->getCurrentUser();

        if (is_object($self)) {
            /*
            |--------------------------------------------------------------------------
            | Permissions for all logged-in users
            |--------------------------------------------------------------------------
            */

            // Shouts
            $authority->allow('create', 'shouts');
            $authority->allow('delete', 'shouts', function ($self, $item) {
                if (is_object($item)) {
                    return ($item->user_id == $self->getCurrentUser()->id);
                }
                if (is_array($item)) {
                    return $self->getCurrentUser()->shouts()->where('id', $item['shouts'])->count();
                }
            });

            // Event Signups
            $authority->allow('create', 'events.signups');
            $authority->allow('delete', 'events.signups', function ($self, $item) {
                if (is_object($item)) {
                    return ($item->user_id == $self->getCurrentUser()->id);
                }
                if (is_array($item)) {
                    return $self->getCurrentUser()->eventSignups()->where('id', $item['signups'])->count();
                }
            });

            // Users
            $authority->allow('delete', 'users', function ($self, $item) {
                if (is_object($item)) {
                    return ($item->id == $self->getCurrentUser()->id);
                }
                if (is_array($item)) {
                    return $self->getCurrentUser()->id == $item['users'];
                }
            });

            /*
            |--------------------------------------------------------------------------
            | Role-based Permissions
            |--------------------------------------------------------------------------
            */
            if ($self->hasRole('Pages Admin')) {
                $authority->allow('manage', 'pages');
            }

            if ($self->hasRole('Shouts Admin')) {
                $authority->allow('manage', 'shouts');
            }

            if ($self->hasRole('Events Admin')) {
                $authority->allow('manage', 'events');
                $authority->allow('manage', 'event-types');
                $authority->allow('manage', 'events.signups');
            }

            if ($self->hasRole('Admin')) {
                $authority->allow('read', 'logs');
                $authority->allow('manage', 'all');

                // can do everything except modify users & roles
                $authority->deny('delete', 'users');
                $authority->deny('manage', 'roles');
                $authority->deny('manage', 'user-roles');
            }

            /*
            |--------------------------------------------------------------------------
            | SuperAdmin Permissions - Keep at Bottom
            |--------------------------------------------------------------------------
            */
            if ($self->hasRole('Super Admin')) {
                $authority->allow('play', 'playlists');
                $authority->allow('manage', 'all');
            }
        }
    },
];