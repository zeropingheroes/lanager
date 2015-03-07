@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')

	@include('user-achievements.partials.list')

	@include('buttons.create', ['resource' => 'user-achievements'])

@endsection
