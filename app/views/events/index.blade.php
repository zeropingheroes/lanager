@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')

	<div class="timetable-nav">
		{{
			Navigation::tabs([
			[
				'title' => 'List',
				'link' => route('events.index', ['tab' => 'list']),
				'active' => (Input::get('tab') == 'list' OR empty(Input::get('tab')) ),
			],
			[
				'title' => 'Timetable',
				'link' => route('events.index', ['tab' => 'timetable']),
				'active' => Input::get('tab') == 'timetable',
			],

			])
		}}
	</div>

	@if ( Input::get('tab') == 'list' OR empty(Input::get('tab')) )
		@include('events.partials.list', ['events' => $events] )
	@elseif ( Input::get('tab') == 'timetable' )
		@include('events.partials.timetable' )
		
	@endif
	@include('buttons.create', ['resource' => 'events'])

@endsection
