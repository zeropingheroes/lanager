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
	| Max Queue Length
	|--------------------------------------------------------------------------
	|
	| Prevent users from submitting new items if the total of unplayed items
	| durations exceeds this value
	|
	*/
	'maxQueueLength' => 3600, // Time in seconds

	/*
	|--------------------------------------------------------------------------
	| Max Item Duration
	|--------------------------------------------------------------------------
	|
	| Prevent users from submitting a playlist item if it is longer than
	| this value
	|
	*/
	'maxItemDuration' => 100, // Time in seconds

);
