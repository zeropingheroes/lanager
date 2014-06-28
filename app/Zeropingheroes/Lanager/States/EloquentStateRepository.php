<?php namespace Zeropingheroes\Lanager\States;

use Zeropingheroes\Lanager\States\State,
	Zeropingheroes\Lanager\Users\User;
use Zeropingheroes\Lanager\States\StateContract;
use DB;
use Illuminate\Support\Collection;

class EloquentStateRepository implements StateContract {

	/**
	 * Generate foundation query for user states now or at given timestamp
	 *
	 * @return object QueryBuilder
	 */
	protected function createStatesQuery($timestamp = '')
	{
		$timestamp = !empty($timestamp) ? $timestamp : time();
		return State::select('states.*')
							->join(
								DB::raw('(
										SELECT max(created_at) max_created_at, user_id
						    			FROM states
						    			WHERE created_at
						    				BETWEEN from_unixtime('.($timestamp-60).') AND from_unixtime('.$timestamp.')
						    			GROUP BY user_id
						    			) s2'),
								function($join)
								{
									$join->on('states.user_id', '=', 's2.user_id')
										 ->on('states.created_at', '=', 's2.max_created_at');
								})
							->orderBy('states.user_id')
							->with('application', 'server', 'user');
	}

	/**
	 * Get states for specified user(s) now or at given timestamp
	 *
	 * @param  object|array   $users|null
	 * @return array|object State
	 */
	public function getUserStates( $users = null, $timestamp = null )
	{
		// No user(s) specified
		if( ! $users )
		{
			return $this->createStatesQuery($timestamp)->get(); // Get states for all users
		}
		
		// One user specified
		if( is_object($users) )
		{
			return $this->createStatesQuery($timestamp)->where('states.user_id', '=', $users->id)->get();
		}		

		// Several users specified
		if( is_array($users) && count($users) > 1 )
		{
			foreach($users as $user)
			{
				$userIds[] = $user->id; // TODO: create a laravel collection instead
			}
			return $this->createStatesQuery($timestamp)->where('states.user_id', '=', $userIds)->get();
		}
	}

	/**
	 * Get application usage now or at given timestamp
	 *
	 * @return array
	 */
	public function getApplicationUsage($applications = null, $timestamp = null)
	{
		$states = $this->createStatesQuery($timestamp)->whereNotNull('states.application_id')->get();

		if( count($states) )
		{
			// Collect and combine states for the same application
			foreach($states as $state)
			{
				// merge states that refer to the same application 
				$combinedUsage[$state->application_id]['application'] = $state->application->toArray();
				// add the state's user as a child of the above application key
				$combinedUsage[$state->application_id]['users'][] = $state->user->toArray();
			}

			// Build clean array of applications
			foreach($combinedUsage as $item)
			{
				$usage[] = array(
					'application'	=> $item['application'],
					'users'			=> $item['users'],
					);
			}

			// Sort applications array by user count, in decending order
			usort($usage, function($a, $b) {
				return count($b['users']) - count($a['users']);
			});
			
			return new Collection($usage);
		}
		return null;
	}


	/**
	 * Get servers being used by users at the timestamp
	 *
	 * @return array
	 */
	public function getServerUsage($servers = null, $timestamp = null)
	{
		$states = $this->createStatesQuery($timestamp)->whereNotNull('states.server_id')->get();

		if( count($states) )
		{
			// Collect and combine states for the same server
			foreach($states as $state)
			{
				// merge states that refer to the same server
				$combinedUsage[$state->server_id]['server'] = $state->server->toArray();
				$combinedUsage[$state->server_id]['application'] = $state->application->toArray();
				// add the state's user as a child of the above server key
				$combinedUsage[$state->server_id]['users'][] = $state->user->toArray();
			}

			// Build clean array of servers
			foreach($combinedUsage as $item)
			{
				$usage[] = array(
					'server'		=> $item['server'],
					'application'	=> $item['application'],
					'users'			=> $item['users'],
					);
			}

			// Sort applications array by user count, in decending order
			usort($usage, function($a, $b) {
				return count($b['users']) - count($a['users']);
			});

			return new Collection($usage);
		}
		return null;
	}

}