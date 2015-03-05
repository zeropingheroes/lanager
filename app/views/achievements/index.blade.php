@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')

	@if(count($achievements))
		<table class="table">
			<thead>
				<tr>
					<th>Name</th>
					<th>Description</th>
					<th>Achieved By</th>
					@if( Authority::can('manage', 'achievements') )
						<th class="text-center">{{ Icon::cog() }}</th>
					@endif
				</tr>
			</thead>
			<tbody>
			@foreach( $achievements as $achievement )
				<tr>
					<td>{{ link_to_route('achievements.show', $achievement->name, $achievement->id) }}</td>
					<td>{{ $achievement->description }}</td>
					<td>
						@if( $achievement->userAchievements->count() > 0 )
							{{ $achievement->userAchievements->count() }}
							{{ str_plural('user', $achievement->userAchievements->count() ) }}
						@endif
					</td>
					@if( Authority::can('manage', 'achievements') )
						<td class="text-center">
							@include(
								'buttons.create',
								[
									'resource' => 'user-achievements',
									'size' => 'extraSmall',
									'icon' => 'user',
									'hover' => 'Award this achievement',
									'parameters' => ['achievement_id' => $achievement->id],
								])
							@include('buttons.edit', ['resource' => 'achievements', 'item' => $achievement, 'size' => 'extraSmall'])
							@include('buttons.destroy', ['resource' => 'achievements', 'item' => $achievement, 'size' => 'extraSmall'])
						</td>
					@endif
				</tr>
			@endforeach
			</tbody>
		</table>
	@else
		<p>No achievements found!</p>
	@endif
	@include('buttons.create', ['resource' => 'achievements'])
@endsection
