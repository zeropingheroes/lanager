@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')

	@if(count($usage))
		<table class="table states-current-usage">
		<?php
			$i = 0;
			$totalUsers = 0;
			foreach ( $usage as $item )
			{
				$users = [];
				foreach ( $item['users'] as $user )
				{
					$users[] = link_to_route('users.show', $user['username'], $user['id']);
				}
				$rows[$i]['application'] = $item['application']['name'];
				$rows[$i]['user-count']	= count($users);
				if( $item['server'] )
				{
					$rows[$i]['address'] = $item['server']['address'];
				}
				$rows[$i]['users'] = implode(', ', $users);
				$i++;
			}
		?>
		@foreach($rows as $row)
			<tr>
				<td class="application">{{ $row['application'] }}</td>
				<td class="user-count">{{ $row['user-count']}}</td>
				<td>{{ $row['address']}}</td>
				<td>{{ $row['users'] }}</td>
			</tr>
		@endforeach
		</table>

	@else
		<p>No usage to show!</p>
	@endif

	@include('usage.partials.last-updated')
	@include('usage.partials.history-links')
@endsection
