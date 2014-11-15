@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')
	@include('shouts.partials.form')
	@include('shouts.partials.list')
	{{ $shouts->links() }}
@endsection
