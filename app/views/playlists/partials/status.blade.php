<?php
if( $playlist->count() )
	switch ($playlist->playback_state)
	{
		case 0:
			$labelType = 'info';
			break;
		case 1:
			$labelType = 'success';
			break;
		default:
			$labelType = 'normal';
			break;
	}

	echo Label::{$labelType}($playlist->present()->playbackStateText);
?>