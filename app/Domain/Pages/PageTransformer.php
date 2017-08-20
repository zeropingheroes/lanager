<?php namespace Zeropingheroes\Lanager\Domain\Pages;

use League\Fractal\TransformerAbstract;

class PageTransformer extends TransformerAbstract
{

    /**
     * Transform resource into standard output format with correct typing
     * @param  object BaseModel   Resource being transformed
     * @return array              Transformed object array ready for output
     */
    public function transform(Page $page)
    {
        return [
            'id' => (int)$page->id,
            'parent_id' => (!is_null($page->parent_id) ? (int)$page->parent_id : null),
            'title' => $page->title,
            'content' => $page->content,
            'children' => $page->children,
            'links' => [
                [
                    'rel' => 'self',
                    'uri' => (url().'/pages/'.$page->id),
                ],
            ],
        ];
    }
}