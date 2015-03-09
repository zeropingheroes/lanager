@if( ! count($state) )
	<p>Status unknown!</p>
@else

	<h3>@include('users.partials.status-label', ['state' => $state])</h3>
	
	@if( isset( $state->application->steam_app_id) )

		<table class="table">
			<thead>
				<tr>
					<th>Game</th>
					<th>Server</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						@include('applications.partials.button', ['application' => $state->application])
					</td>
					<td>
						@if( isset( $state->server->address ) )
							@include('servers.partials.link', ['server' => $state->server])
						@endif
					</td>
				</tr>
			</tbody>
		</table>
	@endif
@endif
