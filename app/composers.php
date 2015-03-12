<?php
/*
|--------------------------------------------------------------------------
| View Composers
|--------------------------------------------------------------------------
*/

View::composer('layouts.default.nav', function($view)
{
	// Info (cached until a page is edited)
	$pageMenu = Cache::rememberForever('pageMenu', function()
	{
		$pages = Zeropingheroes\Lanager\Pages\Page::whereNull('parent_id')->get();
		if( $pages->count() )
		{
			foreach($pages as $page)
			{
				$menuItems[] =
				[
					'title' => $page['title'],
					'link' => URL::route('pages.show', $page->id),
				];
			}
			$menuItems[] = [
				'title' => 'All Pages',
				'link' => URL::route('pages.index'),
			];
		}
		else
		{
			$menuItems = []; // TODO: deal with no pages better
		}
		return $menuItems;
	});


	$view->with('info', $pageMenu);

	// Links
	$view->with('links', Config::get('lanager/links'));
});

View::composer('layouts.default.playlists', function($view)
{
	$playlists = Zeropingheroes\Lanager\Playlists\Playlist::all();

	$view->with('playlists', $playlists);
});