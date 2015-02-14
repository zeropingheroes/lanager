@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')
	@if(count($users))
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
				'User'			=> '<a class="pull-left" href="'.URL::route('users.show', $user->id).'">'.View::make('users.partials.avatar')->withUser($user).' '.e($user->username).'</a>',
				'Status'		=> $status,
				'Game'			=> $application,
				'Server'		=> $server,
			);
		}
		?>
		{{ Table::withContents($tableBody) }}
	@else
		No users found!
	@endif
@endsection
