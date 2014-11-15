@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')
	<div class="timetable-nav">
		{{
			Navigation::tabs([
			[
				'title' => 'Timetable',
				'link' => route('events.index', ['tab' => 'timetable']),
				'active' => (Input::get('tab') == 'timetable' OR empty(Input::get('tab')) ),
			],
			[
				'title' => 'List',
				'link' => route('events.index', ['tab' => 'list']),
				'active' => Input::get('tab') == 'list',
			],
			])
		}}
	</div>
	@if( Input::get('tab') == 'timetable' OR empty(Input::get('tab')) )
		@include('events.partials.timetable' )
	@elseif( Input::get('tab') == 'list' )
		@include('events.partials.list', ['events' => $events] )
	@endif
@endsection
