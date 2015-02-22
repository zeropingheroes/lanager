@if(count($userAchievements))
	<table class="table">
		<thead>
			<tr>
				<th>User</th>
				<th>Achievement</th>
				<th>LAN</th>
				<th>Date</th>
				@if( Authority::can('manage', 'user-achievements'))
					<th>Controls</th>
				@endif
			</tr>
		</thead>
		<tbody>
		@foreach( $userAchievements as $userAchievement )
			<tr>
				<td>
					@include('users.partials.avatar-username', ['user' => $userAchievement->user])
				</td>
				<td>
					<abbr title="{{ $userAchievement->achievement->description }}">{{ $userAchievement->achievement->name }}</abbr>
				</td>
				<td>
					{{ $userAchievement->lan->name}}
				</td>
				<td>
					{{ date('M Y', strtotime($userAchievement->lan->start) ) }}
				</td>
				@if( Authority::can('manage', 'user-achievements'))
					<td>
						{{ HTML::button('user-achievements.edit',$userAchievement->id, ['value' => '']) }}
						{{ HTML::button('user-achievements.destroy',$userAchievement->id, ['value' => '']) }}
					</td>
				@endif
			</tr>
		@endforeach
		</tbody>
	</table>
@else
	No achievements awarded!
@endif
