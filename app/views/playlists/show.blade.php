@extends('layouts.fullscreen')
@section('content')
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
			"youtube-player", {{ Config::get('lanager/playlist.videoplayer.width') }}, {{ Config::get('lanager/playlist.videoplayer.height') }}, "8", null, null, params, atts);

		var playerLoadedVideoId;
		var playerLoadedVideoUniqueId;
		var playerPlaybackState;

		// Called when player loaded for first time
		function onYouTubePlayerReady(playerId)
		{
			youtubePlayer = document.getElementById('youtube-player');
			console.log('Playlist: Embedded video player ready');
			pollPlaylist();
			youtubePlayer.setPlaybackQuality('{{ Config::get('lanager/playlist.videoplayer.quality') }}');
			youtubePlayer.addEventListener('onStateChange', 'onStateChangeHandler');
			youtubePlayer.addEventListener('onError', 'onErrorHandler');
		}

		// Poll database for next item or pausing
		function pollPlaylist()
		{
			var playlistUrl = '{{ route('playlists.show', $playlist->id) }}';
			
			$.getJSON(playlistUrl,function(playlistItem)
			{
				if(playlistItem)
				{
					console.log('Playlist: Polling response OK');

					// Convert playlist item playback state to int
					playlistItem.playlist.playback_state = parseInt(playlistItem.playlist.playback_state);
					
					// check for item type
					if (playlistItem.url.toLowerCase().indexOf("youtube.com") >= 0)
					{
						playlistItem.videoId = extractYouTubeVideoId(playlistItem.url);
					}

					// new playlistItem, or playlistItem skipped/deleted
					if(playlistItem.videoId != playerLoadedVideoId)
					{
						console.log('Playlist: '+playlistItem.videoId+' ['+playlistItem.id+'] - Item retrieved');
						
						// Update variables
						playerLoadedVideoId = playlistItem.videoId;		
						playerLoadedVideoUniqueId = playlistItem.id;				
						
						// Load the video into the player
						console.log('Playlist: '+playlistItem.videoId+' ['+playlistItem.id+'] - Loading into player');
						youtubePlayer.loadVideoById(playlistItem.videoId);
						youtubePlayer.setPlaybackQuality('{{ Config::get('lanager/playlist.videoplayer.quality') }}'); // request best available quality

						// Update the "now playing" display
						updateNowPlayingDisplay(playlistItem);
					}
					if(playlistItem.playlist.playback_state != playerPlaybackState)
					{
						switch(playlistItem.playlist.playback_state)
						{
							case 0: // paused
								console.log('Playlist: '+playlistItem.videoId+' ['+playlistItem.id+'] - Pausing playback');
								youtubePlayer.pauseVideo();
								playerPlaybackState = 0;
								break;
							case 1: // playing
								console.log('Playlist: '+playlistItem.videoId+' ['+playlistItem.id+'] - Starting/resuming playback');
								youtubePlayer.playVideo();
								playerPlaybackState = 1;
								break;
							default:
								console.error('Playlist: '+playlistItem.videoId+' ['+playlistItem.id+'] - Invalid playlist playback state received');
						}
					}
				}
				else
				{
					console.error('Playlist: Polling error - no response');
				}
			});
			setTimeout(pollPlaylist,1000);
		}

		// Perform actions based on player's state changing, e.g. when last video stopped, load the next one
		function onStateChangeHandler(newState) {
			if(playerLoadedVideoId)
			{
				switch(newState)
				{
					case -1:
						console.log('Playlist: '+playerLoadedVideoId+' ['+playerLoadedVideoUniqueId+'] - Player state changed to unstarted');
						break;
					
					case 0:
						console.log('Playlist: '+playerLoadedVideoId+' ['+playerLoadedVideoUniqueId+'] - Player state changed to ended');
						updatePlaybackState(playerLoadedVideoUniqueId, 1); // mark the last video as played
						break;
					
					case 1:
						console.log('Playlist: '+playerLoadedVideoId+' ['+playerLoadedVideoUniqueId+'] - Player state changed to playing');
						break;
					
					case 2:
						console.log('Playlist: '+playerLoadedVideoId+' ['+playerLoadedVideoUniqueId+'] - Player state changed to paused');
						break;
					
					case 3:
						console.log('Playlist: '+playerLoadedVideoId+' ['+playerLoadedVideoUniqueId+'] - Player state changed to buffering');
						break;
					
					case 5:
						console.log('Playlist: '+playerLoadedVideoId+' ['+playerLoadedVideoUniqueId+'] - Player state changed to queued');
						break;
				}
			}
		}

		// Handle errors
		function onErrorHandler(errorNum)
		{
			switch(errorNum)
			{
				case 100: // video not found
					console.error('Playlist: Error '+errorNum+': video not found - skipping');
					updatePlaybackState(playerLoadedVideoUniqueId, 2);
					break;
				case 101:
					console.error('Playlist: Error '+errorNum+': video owner does not allow embedding - skipping');
					updatePlaybackState(playerLoadedVideoUniqueId, 2);
					break;
				case 150:
					console.error('Playlist: Error '+errorNum+': video owner does not allow embedding - skipping');
					updatePlaybackState(playerLoadedVideoUniqueId, 2);
					break;
				default:
					console.error('Playlist: Error '+errorNum+': unknown error');
			}

		}

		// Feed back a video's playback state to the database
		function updatePlaybackState(uniqueVideoId, playbackState)
		{
			if(uniqueVideoId)
			{
				$.ajax({
					url: '{{ route('playlists.playlistitems.update', $playlist->id) }}'+'/'+uniqueVideoId,
					type: 'PUT',
					data: {
						playback_state: playbackState,
					},
					success: function(result) {
						if(result == 1)
						{
							console.log('Playlist: '+playerLoadedVideoId+' ['+uniqueVideoId+'] - Updated item playback state as '+playbackState+'');
						}
						else
						{
							console.warn('Playlist: '+playerLoadedVideoId+' ['+uniqueVideoId+'] - Warning: item already marked as '+playbackState);	
						}
					},
					error: function (request, status, error) {
						console.error('Playlist: '+playerLoadedVideoId+' ['+uniqueVideoId+'] - Error updating playback state: '+error);
					}
				});
			}
		}

		function updateNowPlayingDisplay(playlistItem)
		{
			console.log('Playlist: '+playlistItem.videoId+' ['+playlistItem.id+'] - Updating now playing display');
			nowPlayingDisplay = playlistItem.title;
			submitterDisplay = playlistItem.user.username+'<img src="'+playlistItem.user.avatar+'" alt="Avatr">';
			$('div#now-playing').html(nowPlayingDisplay);
			$('div#submitter').html(submitterDisplay);
		}

		function extractYouTubeVideoId(youTubeUrl){
			var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
			var match = youTubeUrl.match(regExp);
			if (match&&match[2].length==11){
				return match[2];
			}
			else
			{
				console.log('Playlist: Invalid YouTube URL provided');
			}
		}

	</script>
@endsection