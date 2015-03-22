<?php namespace Zeropingheroes\Lanager\ApplicationUsage;

use Zeropingheroes\Lanager\States\StateService;
use Zeropingheroes\Lanager\Applications\ApplicationTransformer,
	Zeropingheroes\Lanager\Users\UserTransformer;

class ApplicationUsageService {

	protected $states;
	protected $applicationTransformer;
	protected $userTransformer;

	public function __construct()
	{
		$this->states = new StateService;
		$this->applicationTransformer = new ApplicationTransformer;
		$this->userTransformer = new UserTransformer;
	}

	public function userTotalsAt( $timestamp )
	{
		$states = $this->states->at( $timestamp )->whereNotNull('states.application_id')->get();

		if( count($states) )
		{
			// Collect and combine states for the same application
			foreach($states as $state)
			{
				// merge states that refer to the same application 
				$combinedUsage[$state->application_id]['application'] = $this->applicationTransformer->transform( $state->application );
				
				// add the state's user as a child of the above application key
				$combinedUsage[$state->application_id]['users'][] = $this->userTransformer->transform( $state->user );
			}

			// Build clean array of applications
			foreach($combinedUsage as $usageItem)
			{
				$usageItem['application']['users'] = $usageItem['users'];
				$applications[] = $usageItem['application'];
			}

			// Sort applications array by user count, in decending order
			usort($applications, function($a, $b) {
				return count($b['users']) - count($a['users']);
			});

			return $applications;
		}
		return [];
	}

}