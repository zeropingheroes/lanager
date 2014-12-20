<?php namespace Zeropingheroes\Lanager\Pages;

use League\Fractal;

class PageTransformer extends Fractal\TransformerAbstract {
	
	public function transform(Page $page)
	{
		return [
			'id'			=> (int) $page->id,
			'parent_id'		=> (int) $page->parent_id,
			'title'			=> $page->title,
			'content'		=> $page->content,
			'links'			=> [
				[
					'rel' => 'self',
					'uri' => ('/pages/'. $page->id),
				]
			],
		];
	}
}