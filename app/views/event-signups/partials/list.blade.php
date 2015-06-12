@if (count($eventSignups))
	<table class="table">
		<thead>
			<tr>
				<th>User</th>
				<th>Signed Up</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		@foreach( $eventSignups as $eventSignup )
			<tr>
				<td>
					@include('users.partials.avatar-username', ['user' => $eventSignup->user ])
				</td>
				<td>
					<span title="{{ $eventSignup->created_at }}">
						{{ $eventSignup->created_at->diffForHumans() }}
					</span>
				</td>
				<td class="text-right">
					@if ( Authority::can('manage', 'events.signups') )
						@include(
							'buttons.destroy',
							[
								'resource' => 'events.signups',
								'item' => $eventSignup,
								'icon' => 'remove',
								'text' => 'Remove Signup',
								'parameters' =>
								[
									'event_id' => $eventSignup->event_id,
									'event_signup_id' => $eventSignup->id,
								],
							])
					@endif
				</td>
			</tr>
		@endforeach
		</tbody>
	</table>
@else
	<p>No signups to show!</p>
@endif