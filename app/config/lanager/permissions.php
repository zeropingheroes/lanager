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
			if ( $self->hasRole('InfoPageAdmin') ) 
			{
				$authority->allow('manage', 'infoPages');
			}

			if ( $self->hasRole('ShoutAdmin') ) 
			{
				$authority->allow('manage', 'shouts');
			}

			if ( $self->hasRole('EventAdmin') ) 
			{
				$authority->allow('manage', 'events');
			}

			// Must be at bottom
			if ( $self->hasRole('SuperAdmin') ) 
			{
				$authority->allow('manage', 'all');
			}
		}
	}
);