<?php
/*
|--------------------------------------------------------------------------
| View Composers
|--------------------------------------------------------------------------
*/

View::composer('layouts.default.infopages', function($view)
{
	$infoPagesMenuItems = Zeropingheroes\Lanager\InfoPages\InfoPage::whereNull('parent_id')->get();

	$view->with('infoPages', $infoPagesMenuItems);
});