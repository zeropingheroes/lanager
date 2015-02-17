<?php

$navbar = 
[
	[
		'title' => 'Events',
		'link' => URL::route('events.index'),
	],
	[
		'title' => 'Users',
		'link' => URL::route('users.index'),
	],
	[
		'title' => 'Games',
		'link' => URL::route('usage.show', 'applications'),
	],
	[
		'title' => 'Servers',
		'link' => URL::route('usage.show', 'servers'),
	],
	[
		'title' => 'Playlists',
		'link' => URL::route('playlists.index'),
	],
	[
		'Info',
		$info,
	],
	[
		'Extras',
		[
			[
				'title' => 'Achievements',
				'link' => URL::route('achievements.index'),
			],
			[
				'title' => 'LANs',
				'link' => URL::route('lans.index'),
			],
		],
	],
	[
		'Links',
		$links,
	],
];

echo Navbar::withBrand('<img src="' . asset('img/logo.png') .'" width="82" height="29" alt="LANager Logo">')
			->withContent(
				Navigation::links($navbar)
			)
			->withContent(View::make('layouts/default/auth'));
