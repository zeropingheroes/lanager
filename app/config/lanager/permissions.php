<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Bans
	|--------------------------------------------------------------------------
	|
	| List the database IDs of users who should not be allowed to perform an
	| action on a resource
	|
	*/
	'banned' => array(
		'create' => array(
			'playlists' => array(
				'items' => array(0),
				),
			'shouts' => array(0),
		),
	),
	
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
	'initialise' => function($authority)
	{
		// Alias all REST verbs to their CRUD counterparts
		$authority->addAlias('create',	array('create', 'store'));
		$authority->addAlias('read', 	array('read', 'index', 'show'));
		$authority->addAlias('update',	array('update', 'edit'));
		$authority->addAlias('delete',	array('delete', 'destroy'));

		// Create "do everything" alias
		$authority->addAlias('manage',	array('create', 'store', 'read', 'index', 'show', 'update', 'edit', 'delete', 'destroy'));
		
		$self = $authority->getCurrentUser();

		if ( is_object($self) )
		{
			/*
			|--------------------------------------------------------------------------
			| Permissions for all logged-in users
			|--------------------------------------------------------------------------
			*/

			// Shouts
			$authority->allow('create', 'shouts');
			if( in_array($self->id, Config::get('lanager/permissions.banned.create.shouts')) )
			{
				$authority->deny('create', 'shouts');
			}

			// Playlist Items			
			$authority->allow('create', 'playlists.items');
			$authority->allow('delete', 'playlists.items', function($self, $itemId)
			{
				return $self->getCurrentUser()->items()->find($itemId);
			});
			if( in_array($self->id, Config::get('lanager/permissions.banned.create.playlists.items')) )
			{
				$authority->deny('create', 'playlists.items');
			}

			// Playlist Item Votes
			$authority->allow('create', 'playlists.item.votes');
			$authority->allow('delete', 'playlists.item.votes');

			// Event Signups
			$authority->allow('create', 'signups');
			$authority->allow('delete', 'signups');

			// Users
			$authority->allow('delete', 'users', function($self, $user) 
			{
				if ( is_object($user) )
				{
					return $self->getCurrentUser()->id === $user->id; // passed entire user object
				}
				else
				{
					return $self->getCurrentUser()->id === $user; // just passed user id
				}
			});
			
			/*
			|--------------------------------------------------------------------------
			| Role-based Permissions
			|--------------------------------------------------------------------------
			*/
			if ( $self->hasRole('Info Admin') ) 
			{
				$authority->allow('manage', 'infopages');
			}

			if ( $self->hasRole('Shouts Admin') ) 
			{
				$authority->allow('manage', 'shouts');
			}

			if ( $self->hasRole('Events Admin') ) 
			{
				$authority->allow('manage', 'events');
			}

			if ( $self->hasRole('Playlists Admin') ) 
			{
				$authority->allow('manage', 'playlists');
				$authority->allow('manage', 'playlists.items');
			}

			if ( $self->hasRole('Admin') ) 
			{
				$authority->allow('manage', 'all');
				$authority->deny('manage', 'roles');
				$authority->deny('manage', 'role-assignments');
			}

			/*
			|--------------------------------------------------------------------------
			| SuperAdmin Permissions - Keep at Bottom
			|--------------------------------------------------------------------------
			*/
			if ( $self->hasRole('Super Admin') ) 
			{
				$authority->allow('manage', 'all');
			}
		}
	}
);