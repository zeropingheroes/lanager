@extends('layouts.default')
@section('content')
	@if(count($usage))
		<table class="table states-current-usage">
		<?php
			$i = 0;
			$totalUsers = 0;
			foreach ( $usage as $item )
			{
				$users = array();
				foreach ( $item['users'] as $user )
				{
					$users[] = link_to_route('users.show', $user['username'], $user['id']);
				}
				$rows[$i]['application'] = '<a href="'.SteamBrowserProtocol::viewAppInStore($item['application']['steam_app_id']).'"><img src="'.$item['application']['small_logo'].'" alt="Game Logo" title="'.e($item['application']['name']).'"></a>';
				$rows[$i]['user-count']	= count($users);
				$rows[$i]['users'] = implode(', ', $users);
				$i++;
			}
		?>
		@foreach($rows as $row)
			<tr>
				<td class="application">{{ $row['application'] }}</td>
				<td class="user-count">{{ $row['user-count']}}</td>
				<td>{{ $row['users'] }}</td>
			</tr>
		@endforeach
		</table>

	@else
		<p>No usage to show!</p>
	@endif
Last updated {{ $lastUpdated->diffForHumans() }}
	@include('usage.history-links')
@endsection
