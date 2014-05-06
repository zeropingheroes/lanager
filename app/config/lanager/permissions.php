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
			'playlist' => array(
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
	|
	*/
	'initialise' => function($authority)
	{
		$authority->addAlias('manage', array('create', 'read', 'update', 'delete'));
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
			$authority->allow('create', 'playlist.items');
			$authority->allow('delete', 'playlist.items', function($self, $itemId)
			{
				return $self->getCurrentUser()->items()->find($itemId);
			});
			if( in_array($self->id, Config::get('lanager/permissions.banned.create.playlist.items')) )
			{
				$authority->deny('create', 'playlist.items');
			}

			// Playlist Item Votes
			$authority->allow('create', 'playlist.item.votes');
			$authority->allow('delete', 'playlist.item.votes');

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
			if ( $self->hasRole('InfoPagesAdmin') ) 
			{
				$authority->allow('manage', 'infopages');
			}

			if ( $self->hasRole('ShoutsAdmin') ) 
			{
				$authority->allow('manage', 'shouts');
			}

			if ( $self->hasRole('EventsAdmin') ) 
			{
				$authority->allow('manage', 'events');
			}

			if ( $self->hasRole('PlaylistsAdmin') ) 
			{
				$authority->allow('manage', 'playlists');
				$authority->allow('manage', 'playlist.items');
			}

			/*
			|--------------------------------------------------------------------------
			| SuperAdmin Permissions - Keep at Bottom
			|--------------------------------------------------------------------------
			*/
			if ( $self->hasRole('SuperAdmin') ) 
			{
				$authority->allow('manage', 'all');
			}
		}
	}
);