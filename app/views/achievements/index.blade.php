@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')
	@if(count($achievements))
		<?php
		foreach( $achievements as $achievement )
		{
			if( Authority::can('manage', 'achievements') )
			{
				$tableBody[] = array(
					'name'				=> link_to_route('achievements.show', $achievement->name, $achievement->id),
					'descrption'		=> e($achievement->description),
					'achieved-count'	=> $achievement->userAchievements->count(),
					'controls'			=> 	Button::normal( Icon::user() . 'Award' )->asLinkTo(URL::route('user-achievements.create', array('achievement_id' => $achievement->id)), 'Award') .
											HTML::button('achievements.edit', $achievement->id ) .  
											HTML::button('achievements.destroy', $achievement->id),
				);
			}
			else
			{
				$tableBody[] = array(
					'name'			=> e($achievement->name),
					'descrption'	=> e($achievement->description),
					'users'			=> $achievement->userAchievements->count(),
				);	
			}
		}
		?>
		{{ Table::withContents($tableBody) }}
		
	@else
		<p>No achievements found!</p>
	@endif
	{{ HTML::button('achievements.create') }}
@endsection
