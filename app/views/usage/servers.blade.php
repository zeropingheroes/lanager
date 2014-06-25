@extends('layouts.default')
@section('content')
	@if(count($servers))
		{{ Table::open(array('class' => 'states-current-usage')) }}
		<?php
			$i = 0;
			$totalUsers = 0;
			foreach ( $servers as $server )
			{
				$users = array();
				foreach ( $server['users'] as $user )
				{
					$users[] = link_to_route('users.show', $user->username, $user->id);
				}
				$rows[$i]['application'] = '<a href="'.SteamBrowserProtocol::viewAppInStore($server['application']->steam_app_id).'"><img src="'.$server['application']->getLogo().'" alt="Game Logo" title="'.e($server['application']->name).'"></a>';
				$rows[$i]['user-count']	= count($users);
				if( $server['server']->getUrl() )
				{
					$rows[$i]['address'] = '<a href="'.$server['server']->getUrl().'" title="Connect to server">'.$server['server']->getFullAddress().'</a>';
				}
				else
				{
					$rows[$i]['address'] = $server['server']->getFullAddress();
				}
				$rows[$i]['users'] = implode(', ', $users);
				$i++;
			}
		?>
		{{ Table::body($rows) }}
		{{ Table::close() }}
	@else
		<p>No usage to show!</p>
	@endif
@endsection
