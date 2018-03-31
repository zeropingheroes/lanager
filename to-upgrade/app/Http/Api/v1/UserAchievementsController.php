<?php namespace Zeropingheroes\Lanager\Http\Api\v1;

use Zeropingheroes\Lanager\Domain\UserAchievements\UserAchievementService;
use Zeropingheroes\Lanager\Domain\UserAchievements\UserAchievementTransformer;
use Zeropingheroes\Lanager\Http\Api\v1\Traits\FlatResourceTrait;

class UserAchievementsController extends ResourceServiceController
{

    use FlatResourceTrait;

    /**
     * Set the service and transformer classes
     */
    public function __construct()
    {
        parent::__construct();
        $this->service = new UserAchievementService;
        $this->transformer = new UserAchievementTransformer;
    }

}