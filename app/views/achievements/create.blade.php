@extends('layouts.default')
@section('content')

<h3>{{ $title }}</h3>

{{ Form::model($achievement, array('route' => 'achievements.store', 'achievement' => $achievement->id)) }}

@include('achievements.form')

{{ Form::close() }}

@endsection