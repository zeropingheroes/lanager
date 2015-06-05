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
		@include('users.partials.ip', ['user' => $user] )
	</div>
	<div class="profile-actions">
		@include('users.partials.actions', ['user' => $user] )
	</div>
	<div class="profile-nav">
		<ul class="nav nav-tabs">
			<li role="presentation" class="<?php if (Input::get('tab') == 'status') echo 'active'; ?>">
				<a href="{{ route('users.show', ['user' => $user->id, 'tab' => 'status'] ) }}">
					Status
				</a>
			</li>
			<li role="presentation" class="<?php if (Input::get('tab') == 'achievements') echo 'active'; ?>">
				<a href="{{ route('users.show', ['user' => $user->id, 'tab' => 'achievements'] ) }}">
					Achievements {{ View::make('badge', ['collection' => $user->userAchievements] ) }}
				</a>
			</li>
			<li role="presentation" class="<?php if (Input::get('tab') == 'shouts') echo 'active'; ?>">
				<a href="{{ route('users.show', ['user' => $user->id, 'tab' => 'shouts'] ) }}">
					Shouts {{ View::make('badge', ['collection' => $user->shouts] ) }}
				</a>
			</li>
			@if( Auth::check() AND $user->id == Auth::user()->id )
				<li role="presentation" class="<?php if (Input::get('tab') == 'api') echo 'active'; ?>">
					<a href="{{ route('users.show', ['user' => $user->id, 'tab' => 'api'] ) }}">
						API
					</a>
				</li>
			@endif
		</ul>
	</div>
	<div class="profile-content">
		@include('layouts.default.alerts')

		@if( Input::get('tab') == 'status' OR empty(Input::get('tab')) )

			@include('users.partials.status', ['state' => $user->state] )

			{{--
				<h3>History</h3>
				@include('users.partials.status-history', ['states' => $user->states()] )
			--}}

		@elseif( Input::get('tab') == 'achievements' )

			@include('user-achievements.partials.list', ['userAchievements' => $user->userAchievements()->orderBy('lan_id','desc')->get()] )

		@elseif( Input::get('tab') == 'shouts' )

			@include('shouts.partials.list', ['shouts' => $user->shouts()->orderBy('created_at','desc')->get()] )

		@elseif( Input::get('tab') == 'api' AND Auth::check() AND $user->id == Auth::user()->id )

			@include('users.partials.api', ['user' => $user])

		@endif

	</div>

@endsection
