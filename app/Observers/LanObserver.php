<?php

namespace Zeropingheroes\Lanager\Observers;

use Zeropingheroes\Lanager\Lan;
use Zeropingheroes\Lanager\Services\CurrentLanService;

class LanObserver
{

    /**
     * @var CurrentLanService
     */
    private $currentLanService;

    /**
     * LanObserver constructor.
     */
    public function __construct()
    {
        $this->currentLanService = new CurrentLanService();
    }
    /**
     * Listen to the Lan saved event.
     *
     * @param  Lan $lan
     * @return void
     */
    public function saved(Lan $lan)
    {
        $this->currentLanService->update();
    }

    /**
     * Listen to the Lan deleted event.
     *
     * @param  Lan $lan
     * @return void
     */
    public function deleted(Lan $lan)
    {
        $this->currentLanService->update();
    }
}