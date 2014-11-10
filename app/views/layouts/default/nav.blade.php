<?php

echo Navbar::withBrand('<img src="' . asset('img/logo.png') .'" width="82" height="29" alt="LANager Logo">')
			->withContent(
				Navigation::links(
					[
						[
							'title' => 'Shouts',
							'link' => URL::route('shouts.index'),
						],
						[
							'title' => 'Events',
							'link' => URL::route('events.timetable'),
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
					]
				)
			)
			->withContent(
				Navigation::links(Config::get('lanager/links'))
			)
			->withContent(View::make('layouts/default/auth'));