<?php namespace Zeropingheroes\Lanager\Http\Api\v1;

use Dingo\Api\Routing\ControllerTrait;
use Illuminate\Routing\Controller;
use Zeropingheroes\Lanager\Domain\ApplicationUsage\ApplicationUsageService;

class ApplicationUsageController extends Controller
{

    use ControllerTrait;

    /**
     * Service class used to get data
     * @var object BaseResourceService
     */
    protected $service;


    /**
     * Set the service classe
     */
    public function __construct()
    {
        $this->service = new ApplicationUsageService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $applicationsInUse = $this->service->userTotalsAt(time());

        return $this->response->array($applicationsInUse);
    }

}