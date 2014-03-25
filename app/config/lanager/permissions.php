<?php

return array(

	'initialise' => function($authority)
	{
		$authority->addAlias('manage', array('create', 'read', 'update', 'delete'));
		$self = $authority->getCurrentUser();

		// If there is a user currently logged in, assign them permissions
		if ( is_object($self) )
		{
			// Allow any user to...
			$authority->allow('create', 'shouts');
			$authority->allow('create', 'playlistitems');

			// Allow any user to delete themselves
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
			
			// Assign extra permissions based on user's roles
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
				$authority->allow('manage', 'playlistitems');
			}

			// Must be at bottom
			if ( $self->hasRole('SuperAdmin') ) 
			{
				$authority->allow('manage', 'all');
			}
		}
	}
);