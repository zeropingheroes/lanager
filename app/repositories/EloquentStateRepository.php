<?php namespace Zeropingheroes\Lanager\Repositories;

use Zeropingheroes\Lanager\Models\State,
	Zeropingheroes\Lanager\Models\User;
use Zeropingheroes\Lanager\Interfaces\StateRepositoryInterface;
use Config;

class EloquentStateRepository implements StateRepositoryInterface {

	protected $maxage;

	public function __construct()
	{
		$this->maxage = Config::get('lanager/states.maxage');
	}

	/**
	 * Generate foundation query for latest states
	 *
	 * @return object QueryBuilder
	 */
	protected function currentStates()
	{
		return State::select('states.*')
							->leftJoin('states as t2', function($join)
							{
								$join->on('states.user_id', '=', 't2.user_id')
									 ->on('states.created_at', '<', 't2.created_at');
							})->whereNull('t2.user_id')
							->where('states.created_at', '>=', date('Y-m-d H:i:s',time()-$this->maxage))
							->whereIn('states.user_id', function($query)
							{
								$query->select('id')
										->from(with(new User)->getTable())
										->where('visible', 1);
							})
							->with('application', 'server', 'user');
	}

	/**
	 * Get current states for specified user(s)
	 *
	 * @param  object|array   $users|null
	 * @return array|object State
	 */
	public function getCurrentUserStates( $users = null)
	{
		// No user(s) specified
		if( ! $users )
		{
			return $this->currentStates()->get();
		}
		
		// One user specified
		if( is_object($users) )
		{
			return $this->currentStates()->where('states.user_id', '=', $users->id)->get();
		}		

		// Several users specified
		if( is_array($users) && count($users) > 1 )
		{
			foreach($users as $user)
			{
				$userIds[] = $user->id;
			}
			return $this->currentStates()->where('states.user_id', '=', $userIds)->get();
		}
	}

	/**
	 * Get applications currently being used by users
	 *
	 * @return array
	 */
	public function getCurrentApplicationUsage()
	{
		$states = $this->currentStates()->whereNotNull('states.application_id')->get();

		if( count($states) )
		{
			// Collect and combine states for the same application
			foreach($states as $state)
			{
				$usage[$state->application_id]['application'] = $state->application;
				$usage[$state->application_id]['users'][] = $state->user;
			}

			// Build clean array of applications
			foreach($usage as $item)
			{
				$applications[] = array(
					'application'	=> $item['application'],
					'users'			=> $item['users'],
					);
			}

			// Sort applications array by user count, in decending order
			usort($applications, function($a, $b) {
				return count($b['users']) - count($a['users']);
			});

			return $applications;
		}
		else
		{
			return NULL;
		}
	}


	/**
	 * Get applications currently being used by users
	 *
	 * @return array
	 */
	public function getCurrentServerUsage()
	{
		$states = $this->currentStates()->whereNotNull('states.server_id')->get();

		if( count($states) )
		{
			// Collect and combine states for the same server
			foreach($states as $state)
			{
				$usage[$state->server_id]['server'] = $state->server;
				$usage[$state->server_id]['application'] = $state->application;
				$usage[$state->server_id]['users'][] = $state->user;
			}

			// Build clean array of servers
			foreach($usage as $item)
			{
				$servers[] = array(
					'server'		=> $item['server'],
					'application'	=> $item['application'],
					'users'			=> $item['users'],
					);
			}

			// Sort applications array by user count, in decending order
			usort($servers, function($a, $b) {
				return count($b['users']) - count($a['users']);
			});

			return $servers;
		}
		else
		{
			return NULL;
		}
	}


}