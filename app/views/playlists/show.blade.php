@extends('lanager-core::layouts.fullscreen')
@section('content')
	<div id="now-playing" class="pull-left">&nbsp;</div>
	<div id="submitter" class="pull-right">&nbsp;</div>
	<div id="youtube-player">
		You need Flash player 8+ and JavaScript enabled to view this video player.
	</div>
	<script type="text/javascript">
		var params = { allowScriptAccess: "always"};
		var atts = { id: "youtube-player" };
		swfobject.embedSWF(
			"http://www.youtube.com/apiplayer?version=3&enablejsapi=1&playerapiid=youtubePlayer&iv_load_policy=3",
			"youtube-player", {{ Config::get('lanager-core::playlistVideoPlayer.width') }}, {{ Config::get('lanager-core::playlistVideoPlayer.height') }}, "8", null, null, params, atts);

		var playerLoadedVideoId;
		var playerLoadedVideoUniqueId;
		var playerPlaybackState;

		// Called when player loaded for first time
		function onYouTubePlayerReady(playerId)
		{
			youtubePlayer = document.getElementById('youtube-player');
			console.log('Playlist: Embedded video player ready');
			pollPlaylist();
			youtubePlayer.setPlaybackQuality('{{ Config::get('lanager-core::playlistVideoPlayer.quality') }}');
			youtubePlayer.addEventListener('onStateChange', 'onStateChangeHandler');
			youtubePlayer.addEventListener('onError', 'onErrorHandler');
		}

		// Poll database for next item or pausing
		function pollPlaylist()
		{
			var url = '{{ route('playlist.item.current', $playlist->id) }}';
			
			$.getJSON(url,function(retrievedPlaylistItem)
			{
				if(retrievedPlaylistItem)
				{
					console.log('Playlist: Polling: Response OK');

					// Convert playback state to int
					retrievedPlaylistItem.playback_state = parseInt(retrievedPlaylistItem.playback_state);
					
					// check for item type
					if (retrievedPlaylistItem.url.toLowerCase().indexOf("youtube.com") >= 0)
					{
						retrievedPlaylistItem.videoId = extractYouTubeVideoId(retrievedPlaylistItem.url);
					}

					// new retrievedPlaylistItem, or retrievedPlaylistItem skipped/deleted
					if(retrievedPlaylistItem.videoId != playerLoadedVideoId)
					{
						console.log('Playlist: Polling: Item retrieved: '+retrievedPlaylistItem.videoId+' (uid:'+playerLoadedVideoUniqueId+')');
						
						// Update variables
						playerLoadedVideoId = retrievedPlaylistItem.videoId;		
						playerLoadedVideoUniqueId = retrievedPlaylistItem.id;				
						
						// Load the video into the player
						loadRetrievedPlaylistItem(retrievedPlaylistItem.videoId);

						// Update the "now playing" display
						updateNowPlayingDisplay(retrievedPlaylistItem);
					}
					if(retrievedPlaylistItem.playback_state != playerPlaybackState)
					{
						console.log('Playlist: Polling: Playback state change detected: Player:'+playerPlaybackState+' Retrieved item:'+retrievedPlaylistItem.playback_state+' (uid:'+playerLoadedVideoUniqueId+')');				
						switch(retrievedPlaylistItem.playback_state)
						{
							case 0: // unplayed
								console.log('Playlist: Polling: Starting playback of unplayed item '+retrievedPlaylistItem.videoId+' (uid:'+playerLoadedVideoUniqueId+')');
								youtubePlayer.playVideo();
								playerPlaybackState = 1;
								break;				
							case 1: // playing
								console.log('Playlist: Polling: Resuming playback of '+retrievedPlaylistItem.videoId+' (uid:'+playerLoadedVideoUniqueId+')');
								youtubePlayer.playVideo();
								playerPlaybackState = 1;
								break;
							case 2: // paused
								console.log('Playlist: Polling: Pausing playback of '+retrievedPlaylistItem.videoId+' (uid:'+playerLoadedVideoUniqueId+')');
								youtubePlayer.pauseVideo();
								playerPlaybackState = 2;
								break;
							default:
								console.error('Playlist: Polling: ERROR - Retrieved item playback state invalid');
						}
					}
				}
				else
				{
					console.error('Playlist: Polling: ERROR - No response');
				}
			});
			setTimeout(pollPlaylist,2000);
		}

		// Load a video into the player by ID
		function loadRetrievedPlaylistItem(videoId)
		{
			console.log('Playlist: Loading '+videoId+' into player (uid:'+playerLoadedVideoUniqueId+')');
			youtubePlayer.loadVideoById(videoId);
			youtubePlayer.setPlaybackQuality('{{ Config::get('lanager-core::playlistVideoPlayer.quality') }}'); // request best available quality
		}

		// Perform actions based on player's state changing, e.g. when last video stopped, load the next one
		function onStateChangeHandler(newState) {
			if(!playerLoadedVideoId)
			{
				playerLoadedVideoId = '(empty)';
			}
			switch(newState)
			{
				case -1: // unstarted
					console.log('Playlist: Item '+playerLoadedVideoId+' (uid:'+playerLoadedVideoUniqueId+') is unstarted ('+newState+')');
					break;
				
				case 0: // ended
					console.log('Playlist: Item '+playerLoadedVideoId+' (uid:'+playerLoadedVideoUniqueId+') has ended (4 / '+newState+')');
					updatePlaybackState(playerLoadedVideoUniqueId, 4); // mark the last video as played
					break;
				
				case 1: // playing
					console.log('Playlist: Item '+playerLoadedVideoId+' (uid:'+playerLoadedVideoUniqueId+') is now playing ('+newState+')');
					updatePlaybackState(playerLoadedVideoUniqueId, 1); // mark the last video as playing
					playerPlaybackState = 1;
					break;
				
				case 2: // paused
					console.log('Playlist: Item '+playerLoadedVideoId+' (uid:'+playerLoadedVideoUniqueId+') is now paused ('+newState+')');
					// playerPlaybackState = 2; // removed due to YT player pausing just before video end and messing up script flow
					break;
				
				case 3: // buffering
					console.log('Playlist: Item '+playerLoadedVideoId+' (uid:'+playerLoadedVideoUniqueId+') is now buffering ('+newState+')');
					break;
				
				case 5: // video cued
					console.log('Playlist: Item '+playerLoadedVideoId+' (uid:'+playerLoadedVideoUniqueId+') has been cued ('+newState+')');
					break;
			}
		}

		// Handle errors
		function onErrorHandler(errorNum)
		{
			switch(errorNum)
			{
				case 100: // video not found
					console.error('Playlist: Error '+errorNum+': video not found - skipping');
					updatePlaybackState(playerLoadedVideoUniqueId, 5);
					break;
				case 101:
					console.error('Playlist: Error '+errorNum+': video owner does not allow embedding - skipping');
					updatePlaybackState(playerLoadedVideoUniqueId, 5);
					break;
				case 150:
					console.error('Playlist: Error '+errorNum+': video owner does not allow embedding - skipping');
					updatePlaybackState(playerLoadedVideoUniqueId, 5);
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
				console.log('Playlist: Updating playback state'); 
				$.ajax({
					url: '{{ route('playlist.item.update', $playlist->id) }}'+'/'+uniqueVideoId,
					type: 'PUT',
					data: {
						playback_state: playbackState,
					},
					success: function(result) {
						if(result == 1)
						{
							console.log('Playlist: Updated item playback state as '+playbackState+' (uid:'+uniqueVideoId+')');
						}
						else
						{
							console.warn('Playlist: Warning: (uid:'+uniqueVideoId+') already marked as '+playbackState);	
						}
					}
				});
			}
		}

		function updateNowPlayingDisplay(retrievedPlaylistItem)
		{
			console.log('Playlist: Updating now playing display');
			nowPlayingDisplay = retrievedPlaylistItem.title;
			submitterDisplay = retrievedPlaylistItem.user.username+'<img src="'+retrievedPlaylistItem.user.avatar+'" alt="Avatr">';
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