<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Video Player
	|--------------------------------------------------------------------------
	|
	| Set the dimensions and preferred quality for the playlist video player
	|
	*/
	'videoplayer' => array(
		'width' => 1200,
		'height' => 700,
		'quality' => 'highres', // small, medium, large, hd720, hd1080, highres or default (see http://bit.ly/1isMjD9 )
	),

	/*
	|--------------------------------------------------------------------------
	| Downvote Skip Threshold
	|--------------------------------------------------------------------------
	|
	| Videos that receive this number of downvotes from users will be skipped
	|
	*/
	'itemDownvoteSkipThreshold' => 4, // Downvote count

);
