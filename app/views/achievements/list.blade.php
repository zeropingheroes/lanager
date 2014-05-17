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
					'controls'		=> HTML::resourceUpdate('achievements',$achievement->id,'Edit') . ' ' . HTML::resourceDelete('achievements',$achievement->id,'Delete')
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