@extends('lanager-core::layouts.default')
@section('content')
	<h2>{{{ $title }}}</h2>
@if(count($itemsInUse))
	
	{{ Table::open(array('class' => 'states-current-usage')) }}

	<?php
		$i = 0;
		$totalUsers = 0;
		foreach ( $itemsInUse as $itemInUse )
		{
			$users = array();

			foreach ( $itemInUse['users'] as $user )
			{
				$users[] = link_to_route('user.show', $user->username, $user->id);
			}

			$rows[$i]['application'] = '<a href="'.SteamBrowserProtocol::viewAppInStore($itemInUse['application']->steam_app_id).'"><img src="'.$itemInUse['application']->getLogo().'" alt="Game Logo" title="'.e($itemInUse['application']->name).'"></a>';

			$rows[$i]['user-count']	= count($users);

			if( isset($itemInUse['server']) )
			{
				if( $itemInUse['server']->getUrl() )
				{
					$rows[$i]['address'] = '<a href="'.$itemInUse['server']->getUrl().'" title="Connect to server">'.$itemInUse['server']->getFullAddress().'</a>';
				}
				else
				{
					$rows[$i]['address'] = $itemInUse['server']->getFullAddress();
				}
			}

			$rows[$i]['users'] = e(implode(', ', $users));

			$i++;
		}

	?>
	{{ Table::body($rows) }}

	{{ Table::close() }}

@else
	<p>No usage to show!</p>
@endif
@endsection

