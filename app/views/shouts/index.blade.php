@extends('layouts.default')
@section('content')
	@include('shouts.form')
	@include('shouts.list')
	{{ $shouts->links() }}
@endsection
