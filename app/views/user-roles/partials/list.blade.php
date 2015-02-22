@if(count($userRoles))
	<table class="table">
		<thead>
			<tr>
				<th>User</th>
				<th>Role</th>
				@if( Authority::can('manage', 'user-roles'))
					<th>Controls</th>
				@endif
			</tr>
		</thead>
		<tbody>
		@foreach( $userRoles as $userRole )
			<tr>
				<td>@include('users.partials.avatar-username', ['user' => $userRole->user])</td>
				<td>{{ $userRole->role->name }}</td>
				@if( Authority::can('manage', 'user-roles'))
					<td>{{ HTML::button('user-roles.destroy',$userRole->id, ['value' => '']) }}</td>
				@endif
			</tr>
		@endforeach
		</tbody>
	</table>
@else
	No roles assigned!
@endif
