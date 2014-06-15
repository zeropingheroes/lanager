@extends('layouts.default')
@section('content')
	{{ Form::model($infoPage, array('route' => 'infopages.store', 'info' => $infoPage->id)) }}
		@include('infopages.form')
	{{ Form::close() }}
@endsection
