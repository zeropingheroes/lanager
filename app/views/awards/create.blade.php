@extends('layouts.default')
@section('content')
	{{ Form::model($award, array('route' => 'awards.store', 'award' => $award->id)) }}
		@include('awards.form')
	{{ Form::close() }}
@endsection
