<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Video Player
	|--------------------------------------------------------------------------
	|
	| Set the dimensions and preferred quality for the playlist video player
	|
	*/
	'videoplayer' =>
	[
		'width'		=> 1000,
		'height'	=> 700,
		'quality'	=> 'highres', // small/medium/large/hd720/hd1080/highres/default - see http://bit.ly/1isMjD9
	],
	'providers' => [
		[
			'domain'	=> 'youtube.com',
			'class'		=> 'Zeropingheroes\\Lanager\\Domain\\PlaylistItems\\YouTubeVideo',
		],
	]

];
