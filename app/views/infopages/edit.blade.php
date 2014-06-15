@extends('layouts.default')
@section('content')
	{{ Form::model($infoPage, array('route' => array('infopages.update', $infoPage->id), 'method' => 'PUT')) }}
		@include('infopages.form')
	{{ Form::close() }}
@endsection
