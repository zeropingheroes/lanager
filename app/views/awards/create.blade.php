@extends('layouts.default')
@section('content')

<h3>{{ $title }}</h3>

{{ Form::model($award, array('route' => 'awards.store', 'award' => $award->id)) }}

@include('awards.form')

{{ Form::close() }}

@endsection