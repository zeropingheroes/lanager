@extends('layouts.default')
@section('content')
	@include('shouts.partials.form')
	@include('shouts.partials.list')
	{{ $shouts->links() }}
@endsection
