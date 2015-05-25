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
		$pages = Zeropingheroes\Lanager\Domain\Pages\Page::whereNull('parent_id')->where('published', true)->orderBy(DB::raw('ISNULL(position)'))->get();
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
