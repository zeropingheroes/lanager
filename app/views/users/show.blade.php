@extends('layouts.default')
@section('content')

	@include('users.partials.private-profile-warning')
	
	<div class="profile-header">
		<div class="profile-avatar">
			@include('users.partials.avatar', ['user' => $user, 'size' => 'large'] )
		</div>
		<h1>
			{{{ $user->username }}}
			@include('roles.partials.badges', ['roles' => $user->roles])
		</h1>
	</div>
	<div class="profile-actions">
		@include('users.partials.actions', ['user' => $user] )
	</div>
	<div class="profile-nav">
		{{
			Navigation::tabs([
			[
				'title' => 'Status',
				'link' => route('users.show', ['user' => $user->id, 'tab' => 'status'] ),
				'active' => (Input::get('tab') == 'status' OR empty(Input::get('tab')) ),
			],
			[
				'title' => 'Achievements' . View::make('badge', ['collection' => $user->userAchievements()] ),
				'link' => route('users.show', ['user' => $user->id, 'tab' => 'achievements'] ),
				'active' => Input::get('tab') == 'achievements',
			],
			[
				'title' => 'Shouts' . View::make('badge', ['collection' => $user->shouts()] ),
				'link' => route('users.show', ['user' => $user->id, 'tab' => 'shouts'] ),
				'active' => Input::get('tab') == 'shouts',
			],
			])
		}}
	</div>
	<div class="profile-content">
		@include('layouts.default.alerts')

		@if( Input::get('tab') == 'status' OR empty(Input::get('tab')) )

			@include('users.partials.status', ['state' => $user->state()] )

			{{--
				<h3>History</h3>
				@include('users.partials.status-history', ['states' => $user->states()] )
			--}}

		@elseif( Input::get('tab') == 'achievements' )

			@include('user-achievements.partials.list', ['userAchievements' => $user->userAchievements()->orderBy('lan_id','desc')->get()] )

		@elseif( Input::get('tab') == 'shouts' )

			@include('shouts.partials.list', ['shouts' => $user->shouts()->orderBy('created_at','desc')->take(3)->get()] )

		@endif

	</div>

@endsection
