@extends('layouts.default')
@section('content')
	<h2>{{{ $title }}}</h2>
	@include('playlist.item.form')
	@include('playlist.item.list')
@endsection