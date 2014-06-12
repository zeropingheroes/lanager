@extends('layouts.default')
@section('content')

<h3>Edit Info Page</h3>

{{ Form::model($infoPage, array('route' => array('infopages.update', $infoPage->id), 'method' => 'PUT')) }}

@include('infopages.form')

{{ Form::close() }}

@endsection