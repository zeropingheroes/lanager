<?php namespace Zeropingheroes\Lanager\Api;

class UsersController extends BaseController {

	protected $resourceTransformer = 'Zeropingheroes\Lanager\Users\UserTransformer';
	
	protected $resourceService = 'Zeropingheroes\Lanager\Users\UserService';

}