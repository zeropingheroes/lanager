<?php
/*
|--------------------------------------------------------------------------
| View Composers
|--------------------------------------------------------------------------
*/

View::composer('layouts.default.infopages', function($view)
{
	$infoPagesMenuItems = Zeropingheroes\Lanager\Models\InfoPage::whereNull('parent_id')->get();

	$view->with('infoPages', $infoPagesMenuItems);
});