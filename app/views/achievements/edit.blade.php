@extends('layouts.default')
@section('content')

<h3>{{ $title }}</h3>

{{ Form::model($achievement, array('route' => array('achievements.update', $achievement->id), 'method' => 'PUT')) }}

@include('achievements.form')

{{ Form::close() }}
@endsection