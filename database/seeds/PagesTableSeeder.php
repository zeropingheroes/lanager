<?php

use Zeropingheroes\Lanager\Domain\Pages\Page;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PagesTableSeeder extends Seeder {

	/**
	 * Seed the pages table with data
	 */
	public function run()
	{
		if ( DB::table('pages')->count() ) return; // don't seed if table is not empty

		$pages = 
			[
				[
					'title' => 'Example',
					'content' =>  'This is an example page.'
				]
			];
		foreach($pages as $page)
		{
			Page::create($page);
		}
	}
}