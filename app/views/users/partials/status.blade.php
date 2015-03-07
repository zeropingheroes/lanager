@if( ! count($state) )
	<p>Status unknown!</p>
@else
	
	<?php
		switch ($state->status)
		{
			case 1:
				$labelType = ( !empty($state->application_id) ) ? 'success' : 'info'; // Online AND In-game != Online AND away
				break;
			case 2:
				$labelType = 'info';
				break;
			case 3:
				$labelType = 'info';
				break;
			case 4:
				$labelType = 'info';
				break;
			case 5:
				$labelType = 'info';
				break;
			case 6:
				$labelType = 'info';
				break;
			case 0:
				$labelType = 'normal';
				break;
			default:
				$labelType = 'normal';
		}
	?>

	<h3>{{ Label::{$labelType}($state->getStatus()) }}</h3>
	
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
						<a href="{{ SteamBrowserProtocol::viewAppInStore($state->application->steam_app_id) }}" title="{{ $state->application->name }}">
							<img src="{{ $state->application->getLogo() }}" alt="Game Logo">
						</a>
					</td>
					<td>
						@if( isset( $state->server->address ) )
							{{ Icon::hdd() }}
							{{ link_to( SteamBrowserProtocol::connectToServer( $state->server->getFullAddress() ), $state->server->getFullAddress(), ['title' => 'Join through Steam'] ) }}
						@endif
					</td>
				</tr>
			</tbody>
		</table>
	@endif
@endif
