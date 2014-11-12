@extends('layouts.default')
@section('content')
	@if(count($achievements))
		<?php
		foreach( $achievements as $achievement )
		{
			if( Authority::can('manage', 'achievements') )
			{
				$tableBody[] = array(
					'name'			=> e($achievement->name),
					'descrption'	=> e($achievement->description),
					'users'			=> $achievement->users->count(),
					'controls'		=> 	Button::normal( Icon::user() . 'Award' )->asLinkTo(URL::route('awards.create', array('achievement_id' => $achievement->id)), 'Award') .
										HTML::button('achievements.edit', $achievement->id ) .  
										HTML::button('achievements.destroy', $achievement->id),
				);
			}
			else
			{
				$tableBody[] = array(
					'name'			=> e($achievement->name),
					'descrption'	=> e($achievement->description),
					'users'			=> $achievement->users->count(),
				);	
			}
		}
		?>
		{{ Table::withContents($tableBody) }}
		
		{{ $achievements->links() }}
	@else
		<p>No achievements found!</p>
	@endif
	{{ HTML::button('achievements.create') }}
@endsection
