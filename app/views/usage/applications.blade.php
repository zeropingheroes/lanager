@extends('layouts.default')
@section('content')
	@if(count($applications))
		{{ Table::open(array('class' => 'states-current-usage')) }}
		<?php
			$i = 0;
			$totalUsers = 0;
			foreach ( $applications as $application )
			{
				$users = array();
				foreach ( $application['users'] as $user )
				{
					$users[] = link_to_route('users.show', $user->username, $user->id);
				}
				$rows[$i]['application'] = '<a href="'.SteamBrowserProtocol::viewAppInStore($application['application']->steam_app_id).'"><img src="'.$application['application']->getLogo().'" alt="Game Logo" title="'.e($application['application']->name).'"></a>';
				$rows[$i]['user-count']	= count($users);
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
