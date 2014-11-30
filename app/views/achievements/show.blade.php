@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')
	<h4>{{{ $achievement->description }}}</h4>
	@if( Authority::can('edit', 'achievement') )
		{{ HTML::button('achievements.edit', $achievement->id) }}
		{{ HTML::button('achievements.destroy', $achievement->id) }}
	@endif
@endsection
