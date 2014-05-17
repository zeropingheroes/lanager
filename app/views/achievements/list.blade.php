@extends('layouts.default')
@section('content')
	<h2>{{{ $title }}}</h2>
	@if(count($achievements))
		{{ Table::open() }}
		{{ Table::headers('Name', 'Description', 'Users') }}
		<?php
		foreach( $achievements as $achievement )
		{
			$tableBody[] = array(
				'name'			=> e($achievement->name),
				'descrption'	=> e($achievement->description),
				'awards'		=> $achievement->awards->count(),
			);
		}
		?>
		{{ Table::body($tableBody) }}
		{{ Table::close() }}
		{{ $achievements->links() }}
	@else
		No achievements found!
	@endif
@endsection