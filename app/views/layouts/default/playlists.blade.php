<li class="dropdown {{ Request::is('playlists*') ? 'active' : '' }}">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown">Playlists
		<b class="caret"></b>
	</a>
	<ul class="dropdown-menu">
		@include('playlists.list')
		@if( Authority::can( 'manage', 'playlists' ) )
			<li>{{ link_to_route('playlists.create', 'Create...') }}</li>
		@endif
	</ul>
</li>