@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')

	<h4>{{{ $achievement->description }}}</h4>

	@if( $achievement->userAchievements->count() )
		<table class="table">
			<thead>
				<tr>
					<th>
						Achievers
					</th>
					<th colspan="2">
						Awarded at
					</th>
					@if( Authority::can('manage', 'user-achievements') )
						<th class="text-center">{{ Icon::cog() }}</th>
					@endif
				</tr>
			</thead>
		@foreach($achievement->userAchievements as $userAchievement)
			<tbody>
				<tr>
					<td>
						@include('users.partials.avatar-username', ['user' => $userAchievement->user])
					</td>
					<td>
						{{ $userAchievement->lan->name }}
					</td>
					<td>
						{{ (new ExpressiveDate($userAchievement->created_at))->format('D g:ia') }}
					</td>
					@if( Authority::can('manage', 'user-achievements') )
						<td class="text-center">
							@include('buttons.edit', ['resource' => 'user-achievements', 'item' => $userAchievement, 'size' => 'extraSmall'])
							@include('buttons.destroy', ['resource' => 'user-achievements', 'item' => $userAchievement, 'size' => 'extraSmall'])
						</td>
					@endif
				</tr>
			</tbody>
		@endforeach
		</table>
	@else
		<p>No-one has been awarded this achievement yet!</p>
	@endif

	@include('buttons.edit', ['resource' => 'achievements', 'item' => $achievement])
	@include('buttons.destroy', ['resource' => 'achievements', 'item' => $achievement])

@endsection
