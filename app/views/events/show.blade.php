@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')
	@if( isset( $event->type->name ) )
		<h4>{{{ $event->type->name }}}</h4>
	@endif
	<div class="row">
		<div class="col-md-6">
			<h4>{{ $event->present()->timespan }}</h4>
		</div>
		<div class="col-md-6">
			<h4 class="pull-right">
				{{ $event->present()->timespanRelativeToNow }} {{ $event->present()->timespanStatusLabel }}
			</h4>
		</div>
	</div>
	<hr>
	{{ Markdown::string($event->description) }}
	<br>
	{{ HTML::button('events.edit', $event->id) }}
	{{ HTML::button('events.destroy', $event->id) }}
	<br>
@endsection
