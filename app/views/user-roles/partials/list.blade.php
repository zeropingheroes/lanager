@if (count($userRoles))
	<table class="table">
		<thead>
			<tr>
				<th>User</th>
				<th>Role</th>
				<th colspan="2">Assigned By</th>
				@if ( Authority::can('manage', 'user-roles') )
					<th class="text-center">{{ Icon::cog() }}</th>
				@endif
			</tr>
		</thead>
		<tbody>
		@foreach( $userRoles as $userRole )
			<tr>
				<td>
					@include('users.partials.avatar-username', ['user' => $userRole->user ])
				</td>
				<td>
					{{{ $userRole->role->name }}}
				</td>
				</td>
				<td>
					@if ( $userRole->assigned_by != NULL )
						@include('users.partials.avatar-username', ['user' => $userRole->assignedBy ])
					@else
						Unknown
					@endif
				</td>
				<td>
					<span title="{{ $userRole->created_at }}">
						{{ $userRole->created_at->diffForHumans() }}
					</span>
				</td>
				@if ( Authority::can('manage', 'user-roles') )
					<td class="text-center">
						@include(
							'buttons.destroy',
							[
								'resource' => 'user-roles',
								'item' => $userRole,
							])
					</td>
				@endif
			</tr>
		@endforeach
		</tbody>
	</table>
@else
	<p>No role assignments to show!</p>
@endif