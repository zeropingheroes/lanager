@extends('layouts.default')
@section('content')

<h3>Create Info Page</h3>

{{ Form::model($infoPage, array('route' => 'infopages.store', 'info' => $infoPage->id)) }}

@include('infopages.form')

{{ Form::close() }}
@endsection