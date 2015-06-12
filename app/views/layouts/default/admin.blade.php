@if( Auth::user()->isAdmin() )
	<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" title="">
			{{ Icon::cog() }} Admin
		</a>
		<ul class="dropdown-menu">
			@if( Authority::can('manage', 'user-roles') )
				<li><a href="{{ route('user-roles.index') }}">{{ Icon::user() }} Manage Role Assignments</a></li>
			@endif
			@if( Authority::can('manage', 'roles') )
				<li><a href="{{ route('roles.index') }}">{{ Icon::user() }} Manage Roles</a></li>
			@endif
			@if( Authority::can('manage', 'events') )
				<li><a href="{{ route('events.create') }}">{{ Icon::calendar() }} Create Event</a></li>
				<li><a href="{{ route('events.index', ['tab' => 'list']) }}">{{ Icon::calendar() }} Manage Events</a></li>
			@endif
			@if( Authority::can('manage', 'event-types') )
				<li><a href="{{ route('event-types.index') }}">{{ Icon::calendar() }} Manage Event Types</a></li>
			@endif
			@if( Authority::can('manage', 'pages') )
				<li><a href="{{ route('pages.create') }}">{{ Icon::file() }} Create Page</a></li>
			@endif
			@if( Authority::can('manage', 'achievements') )
				<li><a href="{{ route('achievements.create') }}">{{ Icon::certificate() }} Create Achievement</a></li>
			@endif
			@if( Authority::can('manage', 'user-achievements') )
				<li><a href="{{ route('user-achievements.create') }}">{{ Icon::certificate() }} Award Achievement</a></li>
			@endif
			@if( Authority::can('manage', 'lans') )
				<li><a href="{{ route('lans.create') }}">{{ Icon::hdd() }} Create LAN</a></li>
				<li><a href="{{ route('lans.index') }}">{{ Icon::hdd() }} Manage LANs</a></li>
			@endif
			@if( Authority::can('manage', 'shouts') )
				<li><a href="{{ route('shouts.index') }}">{{ Icon::bullhorn() }} Moderate Shouts</a></li>
			@endif
			@if( Authority::can('read', 'logs') )
				<li><a href="{{ route('logs.index') }}">{{ Icon::console() }} View Logs</a></li>
			@endif
		</ul>
	</li>
@endif