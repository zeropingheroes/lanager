@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')

	@if(count($users))
		<table class="table users-index">
			<thead>
				<tr>
					<th>User</th>
					<th>Status</th>
					<th>Game</th>
					<th>Server</th>
					<th>Achievements</th>
					@if( Authority::can('manage', 'users') )
						<th class="text-center">{{ Icon::cog() }}</th>
					@endif
				</tr>
			</thead>
			<tbody>
			@foreach( $users as $user )
				<?php $latestState = $user->states()->latest()->first(); ?>
				<tr>
					<td>
						@include('users.partials.avatar-username', ['user' => $user])
						@include('roles.partials.badges', ['roles' => $user->roles])
					</td>
					<td>
						{{{ ( ($latestState) ? $latestState->present()->statusText : '' ) }}}
					</td>
					<td>
						{{ ( ($latestState) ? $latestState->present()->applicationLink : '') }}
					</td>
					<td>
						{{ (($latestState) ? $latestState->present()->serverLink : '') }}
					</td>
					<td>
						@include('plural', ['singular' => 'award', 'collection' => $user->userAchievements()] )
					</td>
					@if( Authority::can('manage', 'users') )
						<td class="text-center">
							@include('buttons.create',
								[
									'resource' => 'user-roles',
									'parameters' =>
										[
											'user_id' => $user->id
										],
									'size' => 'extraSmall',
									'icon' => 'user',
									'hover' => 'Assign a role to this user',
								])
							@include('buttons.destroy', ['resource' => 'users', 'item' => $user, 'size' => 'extraSmall'])
						</td>
					@endif
				</tr>
			@endforeach
			</tbody>
		</table>
	@else
		<p>No users found!</p>
	@endif

@endsection
