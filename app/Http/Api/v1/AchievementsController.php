<?php namespace Zeropingheroes\Lanager\Http\Api\v1;

use Zeropingheroes\Lanager\Domain\Achievements\AchievementService;
use Zeropingheroes\Lanager\Domain\Achievements\AchievementTransformer;
use Zeropingheroes\Lanager\Http\Api\v1\Traits\FlatResourceTrait;

class AchievementsController extends ResourceServiceController
{

    use FlatResourceTrait;

    /**
     * Set the service and transformer classes
     */
    public function __construct()
    {
        parent::__construct();
        $this->service = new AchievementService;
        $this->transformer = new AchievementTransformer;
    }

}