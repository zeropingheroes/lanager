@extends('layouts.default')
@section('content')

	@include('events.partials.header')
	@include('layouts.default.alerts')

	@include('events.partials.time-info')

	{{ Purifier::clean(Markdown::string($event->description), 'markdown') }}

	<hr>

	@if( $event->allowsSignups() )
		@include('event-signups.partials.title')
		@include('event-signups.partials.list', ['eventSignups' => $event->eventSignups] )
		@include('event-signups.partials.signup-button')
		<hr>
	@endif

	@include('buttons.edit', ['resource' => 'events', 'item' => $event])
	@include('buttons.destroy', ['resource' => 'events', 'item' => $event])
@endsection
