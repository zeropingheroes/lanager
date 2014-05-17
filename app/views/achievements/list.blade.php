@extends('layouts.default')
@section('content')
	<h2>{{{ $title }}}</h2>
	@if(count($achievements))
		{{ Table::open(array('class' => 'achievements')) }}
		@if( Authority::can('manage', 'achievements') )
			{{ Table::headers('Name', 'Description', 'Users', 'Controls') }}
		@else
			{{ Table::headers('Name', 'Description', 'Users') }}
		@endif	
		<?php
		foreach( $achievements as $achievement )
		{
			if( Authority::can('manage', 'achievements') )
			{
				$tableBody[] = array(
					'name'			=> e($achievement->name),
					'descrption'	=> e($achievement->description),
					'awards'		=> $achievement->awards->count(),
					'controls'		=> 	Button::link(URL::route('awards.create', array('achievement_id' => $achievement->id)), '' )->with_icon('user')  . ' ' .
										HTML::resourceUpdate('achievements',$achievement->id,'')->with_icon('pencil') . ' ' . 
										HTML::resourceDelete('achievements',$achievement->id, '', 'trash')
,
				);
			}
			else
			{
				$tableBody[] = array(
					'name'			=> e($achievement->name),
					'descrption'	=> e($achievement->description),
					'awards'		=> $achievement->awards->count(),
				);	
			}
		}
		?>
		{{ Table::body($tableBody) }}
		{{ Table::close() }}
		{{ $achievements->links() }}
	@else
		No achievements found!
	@endif
@endsection