<div class="navbar navbar-inverse navbar-static-top" role="navigation">
	<div class="container">
		<div class="navbar-header">
			<a class="navbar-brand" href="/">
				<img src="{{ asset('img/logo.png') }}" alt="LANager Logo">
			</a>
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
		</div>
		<div class="navbar-header navbar-right">
			<ul class="nav navbar-nav">
				@if(Auth::check())
					<li class="dropdown">
						<a href="#" class="dropdown-toggle user-menu" data-toggle="dropdown">
							{{ HTML::userAvatar( Auth::user() ) }} {{{ Auth::user()->username }}} <b class="caret"></b>
						</a>
						<ul class="dropdown-menu">
							<li>{{ link_to_route('users.show', 'Profile',  Auth::user()->id) }}</li>
							<li>{{ link_to_route('users.logout', 'Log Out') }}</li>
						</ul>
					</li>
				@else
					<li>
						<a href="{{ $authUrl }}" class=" steam-signin"><img src="{{ asset('/img/sits_large_noborder.png') }}"></a>
					</li>
				@endif
			</ul>
		</div>
		<div class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				<li class="{{ Request::is('shouts*') ? 'active' : '' }}">
					{{ link_to_route('shouts.index', 'Shouts') }}
				</li>
				<li class="dropdown {{ Request::is('events*') ? 'active' : '' }}">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">Events
						<b class="caret"></b>
					</a>
					<ul class="dropdown-menu">
						<li>
							{{ link_to_route('events.timetable', 'Timetable') }}
						</li>
						<li>
							{{ link_to_route('events.index', 'List') }}
						</li>
						@if( Authority::can( 'manage', 'events' ) )
							<li>
								{{ link_to_route('events.create', 'Create...') }}
							</li>
						@endif
					</ul>
				</li>
				<li class="{{ Request::is('users*') ? 'active' : '' }}">
					{{ link_to_route('users.index', 'People') }}
				</li>
				<li class="{{ Request::is('statistics/applications/current-usage*') ? 'active' : '' }}">
					{{ link_to_route('statistics.applications.current-usage', 'Games') }}
				</li>
				<li class="{{ Request::is('statistics/servers/current-usage*') ? 'active' : '' }}">
					{{ link_to_route('statistics.servers.current-usage', 'Servers') }}
				</li>
				<li class="dropdown {{ Request::is('playlists*') ? 'active' : '' }}">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">Playlist
						<b class="caret"></b>
					</a>
					<ul class="dropdown-menu">
						<li>
							{{ link_to_route('playlists.items.index', 'Upcoming', 1) }}
						</li>
						<li>
							{{ link_to_route('playlists.items.index', 'History', array('playlist' => 1, 'history' => 1 ) ) }}
						</li>
						@if( Authority::can( 'manage', 'playlists' ) )
							<li>
								{{ link_to_route('playlists.show', 'Screen', 1) }}
							</li>
						@endif

					</ul>
				</li>	
				@include('layouts.default.infopages')
				@include('layouts.default.links')
				@if( Authority::can( 'manage', 'achievements' ) )
					<li class="dropdown {{ Request::is('achievements*') ? 'active' : '' }}">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">Achievements
							<b class="caret"></b>
						</a>
						<ul class="dropdown-menu">
							<li>
								{{ link_to_route('achievements.index', 'Achievement List' ) }}
							</li>
							<li>
								{{ link_to_route('awards.create', 'Award Achievement' ) }}
							</li>
							<li>
								{{ link_to_route('achievements.create', 'Create Achievement' ) }}
							</li>
						</ul>
					</li>	
				@else
					<li class="{{ Request::is('achievements*') ? 'active' : '' }}">
						{{ link_to_route('achievements.index', 'Achievements') }}
					</li>
				@endif
			</ul>
		</div>
	</div>
</div>
