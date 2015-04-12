<?php

return array(
	
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
		$authority->allow('read', 'achievements');
		$authority->allow('read', 'events');
		$authority->allow('read', 'event-types');
		$authority->allow('read', 'events.signups');
		$authority->allow('read', 'lans');
		$authority->allow('read', 'pages');
		$authority->allow('read', 'playlists');
		$authority->allow('read', 'playlists.items');
		$authority->allow('read', 'roles');
		$authority->allow('read', 'shouts');
		$authority->allow('read', 'usage');
		$authority->allow('read', 'users');
		$authority->allow('read', 'user-achievements');
		$authority->allow('read', 'user-roles');

		if ( is_object($self) )
		{
			/*
			|--------------------------------------------------------------------------
			| Permissions for all logged-in users
			|--------------------------------------------------------------------------
			*/

			// Shouts
			$authority->allow('create', 'shouts');
			$authority->allow('delete', 'shouts', function($self, $item)
			{
				if ( is_object($item) ) return ($item->user_id == $self->getCurrentUser()->id);
				if ( is_array($item) ) return $self->getCurrentUser()->shouts()->where('id', $item['shouts'])->count();
			});

			// Playlist Items			
			$authority->allow('create', 'playlists.items');
			$authority->allow('delete', 'playlists.items', function($self, $item)
			{
				if ( is_object($item) ) return ($item->user_id == $self->getCurrentUser()->id);
				if ( is_array($item) ) return $self->getCurrentUser()->playlistItems()->where('id', $item['items'])->count();
			});


			// Playlist Item Votes
			$authority->allow('create', 'playlists.items.votes');
			$authority->allow('delete', 'playlists.items.votes', function($self, $item)
			{
				if ( is_object($item) ) return ($item->user_id == $self->getCurrentUser()->id);
				if ( is_array($item) ) return $self->getCurrentUser()->playlistItemVotes()->where('id', $item['votes'])->count();
			});

			// Event Signups
			$authority->allow('create', 'events.signups');
			$authority->allow('delete', 'events.signups', function($self, $item) 
			{
				if ( is_object($item) ) return ($item->user_id == $self->getCurrentUser()->id);
				if ( is_array($item) ) return $self->getCurrentUser()->eventSignups()->where('id', $item['signups'])->count();
			});

			// Users
			$authority->allow('delete', 'users', function($self, $item) 
			{
				if ( is_object($item) ) return ($item->id == $self->getCurrentUser()->id);
				if ( is_array($item) ) return $self->getCurrentUser()->id == $item['users'];
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
				$authority->allow('manage', 'event-types');
				$authority->allow('manage', 'events.signups');
			}

			if ( $self->hasRole('Playlists Admin') ) 
			{
				$authority->allow('play', 'playlists');
				$authority->allow('manage', 'playlists');
				$authority->allow('manage', 'playlists.items');
				$authority->allow('manage', 'playlists.items.votes');
			}

			if ( $self->hasRole('Admin') )
			{
				$authority->allow('play', 'playlists');
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
			if ( $self->hasRole('Super Admin') ) 
			{
				$authority->allow('play', 'playlists');
				$authority->allow('manage', 'all');
			}
		}
	}
);