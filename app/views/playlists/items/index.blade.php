@extends('lanager-core::layouts.default')
@section('content')
	<h2>{{{ $title }}}</h2>
	@include('lanager-core::playlist.item.form')
	@include('lanager-core::playlist.item.list')
@endsection