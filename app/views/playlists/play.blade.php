@extends('layouts.fullscreen')
@section('content')
	<?php
		$pollUrl = url().'/api/playlists/'. $playlist->id.'/';
		$apiKey = Auth::user()->api_key;
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
			playout.player.object = document.getElementById('youtube-player');
			console.log('Playlist: Embedded video player ready');
			playout.player.object.addEventListener('onStateChange', 'playout.player.handle_state_change');
			playout.player.object.addEventListener('onError', 'playout.player.handle_error');
			playout.poll();
		}

		var playout = {
			playlist: null,
			item: null,
			console: {
				prefix: function()
				{
					return 'Playlist: ' + playout.item.youtube_id + ' ['+playout.item.id+'] - ';
				},
				log: function(message)
				{
					console.log(playout.console.prefix()+message);
				},
				error: function(message)
				{
					console.error(playout.console.prefix()+message);
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
					if(playout.player.loaded_video.youtube_id)
					{
						switch(newState)
						{
							case -1:
								state = 'unstarted';
								break;
							case 0:
								state = 'ended';
								playout.update_database(1, null); // mark the last video as played
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
						playout.console.log('Player state changed to '+state);
					}
				},
				handle_error: function (errorNum) {
					switch(errorNum)
					{
						case 100: // video not found
							message = 'Video not found';
							playout.update_database(2, message);
							break;
						case 101:
							message = 'Video owner does not allow embedding';
							playout.update_database(2, message);
							break;
						case 150:
							message = 'Video owner does not allow embedding';
							playout.update_database(2, message);
							break;
						default:
							message = 'Unknown error';
							alert('Unknown error');
					}
					playout.console.error(message+ ' - skipping');
				}
			},
			poll: function()
			{
				setTimeout(playout.poll,1000);
				$.getJSON( '{{ $pollUrl }}', function( response )
				{
					if( response.length === 0)
					{
						console.log('Playlist: Polling: No data received - nothing to play');
					}
					else
					{
						playout.playlist = response;
						playout.item = response.currentItem;

						playout.item.youtube_id = playout.extract_youtube_id(playout.item.url);

						// Check for change of video
						if( (playout.item.youtube_id != playout.player.loaded_video.youtube_id) || (playout.item.id != playout.player.loaded_video.id))
						{
							playout.console.log('New item retrieved');
							playout.update_title();
							playout.player.loaded_video.youtube_id = playout.item.youtube_id;	
							playout.player.loaded_video.id = playout.item.id;	
							
							playout.console.log('Loading into player');
							playout.player.object.loadVideoById(playout.item.youtube_id);
							playout.player.object.setPlaybackQuality('{{ Config::get('lanager/playlist.videoplayer.quality') }}');
							
							if(playout.playlist.playback_state == 0) // if the playlist is paused
							{
								playout.player.object.pauseVideo(); // pause the video after loading
								playout.player.playback_state = 0;
							}
						}
						else
						{
							console.log('Playlist: Polling: Received item already loaded into player');
						}

						// Check for playlist pause / resume
						if(playout.playlist.playback_state != playout.player.playback_state)
						{
							switch(playout.playlist.playback_state)
							{
								case 0: // paused
									playout.console.log('Playlist state has changed to paused');
									playout.player.object.pauseVideo();
									playout.player.playback_state = 0;
									break;
								case 1: // playing
									playout.console.log('Playlist state has changed to playing');
									playout.player.object.playVideo();
									playout.player.playback_state = 1;
									break;
								default:
									playout.console.error('Playlist state invalid');
									playout.player.playback_state = -1;
							}
						}
					}
				}).error(
					function(jqXHR, textStatus, errorThrown)
					{
						playout.console.error('Polling error: '+errorThrown);
					}
				);
			},
			update_title: function() {
				playout.console.log('Updating title display');
				$('div#now-playing').html("<strong>"+playout.playlist.name + ':</strong> ' +playout.item.title); // todo: escape title
				$('div#submitter').html(playout.item.user.username+'<img src="'+playout.item.user.avatar+'" alt="Avatar">');
			},
			update_database: function (playback_state, skip_reason)
			{
				skip_reason = typeof skip_reason !== 'undefined' ? skip_reason : null;
				$.ajax({
					beforeSend: function (request)
					{
						request.setRequestHeader('Authorization', 'Lanager {{ $apiKey }}' );
					},
					url: '{{ url() }}/api/playlists/' + playout.playlist.id + '/items/' + playout.item.id,
					type: 'PUT',
					data: {
						playback_state: playback_state,
						skip_reason: skip_reason
					},
					error: function (request, status, error) {
						playout.console.error('Error updating playback state: '+error);
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
					playout.console.log('Invalid YouTube URL provided');
				}
			}
		};
	</script>
@endsection
