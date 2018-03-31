<?php namespace Zeropingheroes\Lanager\Domain\EventSignups;

use League\Fractal\TransformerAbstract;
use Zeropingheroes\Lanager\Domain\Users\UserTransformer;

class EventSignupTransformer extends TransformerAbstract
{

    /**
     * Default related resources to include in transformed output
     * @var array
     */
    protected $defaultIncludes = [
        'user',
    ];

    /**
     * Transform resource into standard output format with correct typing
     * @param  object BaseModel   Resource being transformed
     * @return array              Transformed object array ready for output
     */
    public function transform(EventSignup $eventSignup)
    {
        return [
            'id' => (int)$eventSignup->id,
            'event_id' => (int)$eventSignup->event_id,
            'user_id' => (int)$eventSignup->user_id,
            'links' => [
                [
                    'rel' => 'self',
                    'uri' => (url().'/events/'.$eventSignup->event_id.'/signups/'.$eventSignup->id),
                ],
            ],
        ];
    }

    /**
     * Pull in and transform the specified resource
     * @param  object BaseModel   Model being pulled in
     * @return array              Transformed model
     */
    public function includeUser(EventSignup $eventSignup)
    {
        return $this->item($eventSignup->user()->first(), new UserTransformer);
    }
}