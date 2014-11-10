@if( ! count($state) )
	<p>Status unknown!</p>
@else
	<ul>
		@if( isset( $state->application->steam_app_id) )
		<li>
			{{ $state->getStatus() }}: {{{ $state->application->name }}}
		</li>
			<li>
				<a href="{{ SteamBrowserProtocol::viewAppInStore($state->application->steam_app_id) }}">
					<img src="{{ $state->application->getLogo() }}" alt="Game Logo">
				</a>
			</li>
			@if( isset( $state->server->address ) )
				<li>
					Server: {{ link_to( SteamBrowserProtocol::connectToServer( $state->server->getFullAddress() ), $state->server->getFullAddress() ) }}
				</li>
			@endif
		@else
			<li>
				{{ $state->getStatus() }}
			</li>
		@endif
	</ul>
@endif
