@extends('layouts.default')
@section('content')

	@if($user->steam_visibility == 1 AND Auth::check() AND $user->id == Auth::user()->id)
		{{ Alert::danger('<strong>Oh no!</strong> Your Steam Profile is set to private or friends only. This means the LANager can\'t add you to the "popular games" or "popular servers" pages - please consider ' .
		link_to(SteamBrowserProtocol::openSteamPage('SteamIDEditPage'), 'changing your profile\'s privacy settings to public') . ', even if it\'s just for the event. Thanks!' ) }}
	@endif

	<div class="profile-header ">
		<div class="profile-avatar">@include('users.partials.avatar', ['user' => $user, 'size' => 'large'] )</div>
		<h1>{{ $title }}@include('roles.partials.badges', ['roles' => $user->roles])</h1>
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
				'title' => 'Achievements',
				'link' => route('users.show', ['user' => $user->id, 'tab' => 'achievements'] ),
				'active' => Input::get('tab') == 'achievements',
			],
			[
				'title' => 'Shouts',
				'link' => route('users.show', ['user' => $user->id, 'tab' => 'shouts'] ),
				'active' => Input::get('tab') == 'shouts',
			],
			])
		}}
	</div>
	<div class="profile-content">
		@include('layouts.default.alerts')
		@if( Input::get('tab') == 'status' OR empty(Input::get('tab')) )
			<h2>Status</h2>
			@include('users.partials.status', ['state' => $user->states()->latest()->first()] )
			@include('users.partials.actions', ['user' => $user] )
		@elseif( Input::get('tab') == 'achievements' )
			<h2>Achievements</h2>
			@include('user-achievements.partials.list', ['userAchievements' => $user->userAchievements()->orderBy('lan_id','desc')->get()] )
		@elseif( Input::get('tab') == 'shouts' )
			<h2>Shouts</h2>
			@include('shouts.partials.list', ['shouts' => $user->shouts()->orderBy('created_at','desc')->take(3)->get()] )
		@endif
	</div>

@endsection
