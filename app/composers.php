<?php
/*
|--------------------------------------------------------------------------
| View Composers
|--------------------------------------------------------------------------
*/

View::composer('layouts.default.nav', function($view)
{
	// Info
	$infoPages = Zeropingheroes\Lanager\InfoPages\InfoPage::whereNull('parent_id')->get();
	foreach($infoPages as $infoPage)
	{
		$menuItems[] =
		[
			'title' => $infoPage['title'],
			'link' => URL::route('infopages.show', $infoPage->id),
		];
	}
	$view->with('info', $menuItems);

	// Links
	$view->with('links', Config::get('lanager/links'));
});

View::composer('layouts.default.playlists', function($view)
{
	$playlists = Zeropingheroes\Lanager\Playlists\Playlist::all();

	$view->with('playlists', $playlists);
});