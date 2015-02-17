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

		/*
		|--------------------------------------------------------------------------
		| Permissions for un-authenticated users
		|--------------------------------------------------------------------------
		*/
		$authority->allow('read', 'shouts');
		$authority->allow('read', 'playlists');
		$authority->allow('read', 'playlists.items');
		$authority->allow('read', 'pages');
		$authority->allow('read', 'usage');
		$authority->allow('read', 'events');
		$authority->allow('read', 'events.signups');
		$authority->allow('read', 'achievements');
		$authority->allow('read', 'users');

		if ( is_object($self) )
		{
			/*
			|--------------------------------------------------------------------------
			| Permissions for all logged-in users
			|--------------------------------------------------------------------------
			*/

			// Shouts
			$authority->allow('create', 'shouts');
			if( in_array($self->id, Config::get('lanager/permissions.banned.create.shouts')) ) // bans
			{
				$authority->deny('create', 'shouts');
			}

			// Playlist Items			
			$authority->allow('create', 'playlists.items');
			$authority->allow('delete', 'playlists.items', function($self, $itemId)
			{
				return $self->getCurrentUser()->playlistItems()->find($itemId);
			});
			if( in_array($self->id, Config::get('lanager/permissions.banned.create.playlists.items')) ) // bans
			{
				$authority->deny('create', 'playlists.items');
			}

			// Playlist Item Votes
			$authority->allow('create', 'playlists.items.votes');
			$authority->allow('delete', 'playlists.items.votes', function($self, $itemId)
			{
				return $self->getCurrentUser()->playlistItemVotes()->find($itemId);
			});

			// Event Signups
			$authority->allow('create', 'events.signups');
			$authority->allow('delete', 'events.signups'); // TODO: users should only be able to delete their own signups

			// Users
			$authority->allow('delete', 'users', function($self, $user) 
			{
				if ( is_object($user) ) // users can only delete themselves
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
			if ( $self->hasRole('Pages Admin') ) 
			{
				$authority->allow('manage', 'pages');
			}

			if ( $self->hasRole('Shouts Admin') ) 
			{
				$authority->allow('manage', 'shouts');
			}

			if ( $self->hasRole('Events Admin') ) 
			{
				$authority->allow('manage', 'events');
				$authority->allow('manage', 'events.signups');
			}

			if ( $self->hasRole('Playlists Admin') ) 
			{
				$authority->allow('play', 'playlists');
				$authority->allow('manage', 'playlists');
				$authority->allow('manage', 'playlists.items');
			}

			if ( $self->hasRole('Admin') )
			{
				$authority->allow('manage', 'all');

				// can do everything except modify roles
				$authority->deny('manage', 'roles');
				$authority->deny('manage', 'user-roles');
			}

			/*
			|--------------------------------------------------------------------------
			| SuperAdmin Permissions - Keep at Bottom
			|--------------------------------------------------------------------------
			*/
			if ( $self->hasRole('Super Admin') ) 
			{
				$authority->allow('play', 'playlists');
				$authority->allow('manage', 'all');
			}
		}
	}
);