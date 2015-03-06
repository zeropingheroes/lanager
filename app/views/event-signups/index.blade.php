@extends('layouts.default')
@section('content')
	
	<h1>Signups: {{ link_to_route('events.show', $event->name, $event->id) }}</h1>

	@include('layouts.default.alerts')

	@include('event-signups.partials.list')


	@if( ! $event->hasSignupFromUser(Auth::user()->id) )
		@include(
			'buttons.store',
			[
				'resource' => 'events.signups',
				'text' => 'Sign up',
				'size' => 'normal',
				'icon' => 'plus',
				'hover' => 'Sign up to this event',
				'parameters' => ['event_id' => $event->id],
			])
	@endif

@endsection
