@extends('lanager-core::layouts.default')
@section('content')
	<h2>{{{ $title }}}</h2>
	@if(count($users))
		{{ Table::open() }}
		<?php
		foreach( $users as $user )
		{
			// Reset variables
			$status	= NULL;
			$application = NULL;
			$server = NULL;

			// Get user's most recent state
			$state = $user->states()->latest()->first();
			
			// If user has a state
			if( count($state) )
			{
				$status = $state->getStatus();
				
				// If user is running a Steam application
				if ( isset($state->application->steam_app_id) )
				{
					$application = link_to( SteamBrowserProtocol::viewAppInStore( $state->application->steam_app_id ), $state->application->name );

					// If user is connected to a server 
					if ( isset($state->server->address) )
					{
						$server = link_to( SteamBrowserProtocol::connectToServer( $state->server->getFullAddress() ), $state->server->getFullAddress() );
					}
				}
			}

			$tableBody[] = array(
				'user'			=> '<a class="pull-left" href="'.URL::route('user.show', $user->id).'">'.HTML::userAvatar($user).' '.e($user->username).'</a>',
				'status'		=> $status,
				'application'	=> $application,
				'server'		=> $server,
			);
		}
		?>
		{{ Table::body($tableBody) }}
		{{ Table::close() }}
		{{ $users->links() }}
	@else
		No users found!
	@endif
@endsection