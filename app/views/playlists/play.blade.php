@extends('layouts.fullscreen')
@section('content')
	<?php
		$pollUrl = url().'/api/playlists/'. $playlist->id.'/items/?orderBy=created_at&playback_state=0&take=1'; // waiting for api route fix
	?>
	<div style="width: {{ Config::get('lanager/playlist.videoplayer.width') }}px">
		<div id="now-playing" class="pull-left">&nbsp;</div>
		<div id="submitter" class="pull-right">&nbsp;</div>
	</div>
	<div id="youtube-player">
		You need Flash player 8+ and JavaScript enabled to view this video player.
	</div>
	<script type="text/javascript">
		var params = { allowScriptAccess: "always"};
		var atts = { id: "youtube-player" };
		swfobject.embedSWF(
			"http://www.youtube.com/apiplayer?version=3&enablejsapi=1&playerapiid=youtubePlayer&iv_load_policy=3",
			"youtube-player",
			{{ Config::get('lanager/playlist.videoplayer.width') }},
			{{ Config::get('lanager/playlist.videoplayer.height') }},
			"8", null, null, params, atts);

		// Called when player loaded for first time
		function onYouTubePlayerReady(playerId)
		{
			playlist.player.object = document.getElementById('youtube-player');
			console.log('Playlist: Embedded video player ready');
			playlist.player.object.addEventListener('onStateChange', 'playlist.player.handle_state_change');
			playlist.player.object.addEventListener('onError', 'playlist.player.handle_error');
			playlist.poll();
		}

		var playlist = {
			item: null,
			title: null,
			console: {
				prefix: function()
				{
					return 'Playlist: ' + playlist.item.youtube_id + ' ['+playlist.item.id+'] - ';
				},
				log: function(message)
				{
					console.log(playlist.console.prefix()+message);
				},
				error: function(message)
				{
					console.error(playlist.console.prefix()+message);
				}
			},
			player: {
				object: null,
				loaded_video: {
					youtube_id: null,
					id: null,
				},
				playback_state: -1,
				handle_state_change: function (newState) {
					if(playlist.player.loaded_video.youtube_id)
					{
						switch(newState)
						{
							case -1:
								state = 'unstarted';
								break;
							case 0:
								state = 'ended';
								playlist.update_database(1, null); // mark the last video as played
								break;
							case 1:
								state = 'playing';
								break;
							case 2:
								state = 'paused';
								break;
							case 3:
								state = 'buffering';
								break;
							case 5:
								state = 'queued';
								break;
							default:
								state = 'unknown';
						}
						playlist.console.log('Player state changed to '+state);
					}
				},
				handle_error: function (errorNum) {
					switch(errorNum)
					{
						case 100: // video not found
							message = 'Video not found';
							playlist.update_database(2, message);
							break;
						case 101:
							message = 'Video owner does not allow embedding';
							playlist.update_database(2, message);
							break;
						case 150:
							message = 'Video owner does not allow embedding';
							playlist.update_database(2, message);
							break;
						default:
							message = 'Unknown error';
							alert('Unknown error');
					}
					playlist.console.error(message+ ' - skipping');
				}
			},
			poll: function()
			{
				setTimeout(playlist.poll,1000);
				$.getJSON( '{{ $pollUrl }}', function( item )
				{
					if( item.data.length === 0)
					{
						console.log('Playlist: Polling: No item received - nothing to play');
					}
					else
					{
						item = item.data[0]; // extract data
						
						playlist.item = item; // move into parent object
						playlist.item.playlist = playlist.item.playlist;

						playlist.item.youtube_id = playlist.extract_youtube_id(playlist.item.url);

						// Check for change of video
						if( (playlist.item.youtube_id != playlist.player.loaded_video.youtube_id) || (playlist.item.id != playlist.player.loaded_video.id))
						{
							playlist.console.log('New item retrieved');
							playlist.update_title();
							playlist.player.loaded_video.youtube_id = playlist.item.youtube_id;	
							playlist.player.loaded_video.id = playlist.item.id;	
							
							playlist.console.log('Loading into player');
							playlist.player.object.loadVideoById(playlist.item.youtube_id);
							playlist.player.object.setPlaybackQuality('{{ Config::get('lanager/playlist.videoplayer.quality') }}');
							
							if(playlist.item.playlist.playback_state == 0) // if the playlist is paused
							{
								playlist.player.object.pauseVideo(); // pause the video after loading
								playlist.player.playback_state = 0;
							}
						}
						else
						{
							console.log('Playlist: Polling: Received item already loaded into player');
						}

						// Check for playlist pause / resume
						if(playlist.item.playlist.playback_state != playlist.player.playback_state)
						{
							switch(playlist.item.playlist.playback_state)
							{
								case 0: // paused
									playlist.console.log('Playlist state has changed to paused');
									playlist.player.object.pauseVideo();
									playlist.player.playback_state = 0;
									break;
								case 1: // playing
									playlist.console.log('Playlist state has changed to playing');
									playlist.player.object.playVideo();
									playlist.player.playback_state = 1;
									break;
								default:
									playlist.console.error('Playlist state invalid');
									playlist.player.playback_state = -1;
							}
						}
					}
				}).error(
					function(jqXHR, textStatus, errorThrown)
					{
						playlist.console.error('Polling error: '+errorThrown);
					}
				);
			},
			update_title: function() {
				playlist.console.log('Updating title display');
				$('div#now-playing').html("<strong>"+playlist.item.playlist.name + ':</strong> ' +playlist.item.title);
				$('div#submitter').html(playlist.item.user.username+'<img src="'+playlist.item.user.avatar+'" alt="Avatar">');
			},
			update_database: function (playback_state, skip_reason)
			{
				skip_reason = typeof skip_reason !== 'undefined' ? skip_reason : NULL;
				$.ajax({
					url: '{{ route('playlists.items.update', $playlist->id) }}'+'/'+playlist.item.id+'/', // todo: use API route
					type: 'PUT',
					data: {
						playback_state: playback_state,
						skip_reason: skip_reason
					},
					success: function(updated_item) {
						if(updated_item.playback_state == playback_state ) 
						{
							playlist.console.log('Updated playback state: ' + playback_state);
						}
						else
						{
							playlist.console.error('Playback state not updated - response: ' + updated_item);
						}
					},
					error: function (request, status, error) {
						playlist.console.error('Error updating playback state: '+error);
					}
				});
			},
			extract_youtube_id:	function (url){
				regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
				match = url.match(regExp);
				if (match&&match[2].length==11){
					return match[2];
				}
				else
				{
					playlist.console.log('Invalid YouTube URL provided');
				}
			}
		};
	</script>
@endsection
