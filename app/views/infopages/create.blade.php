@extends('layouts.default')
@section('content')

<h3>Create Info Page</h3>

{{ Form::model($infoPage, array('route' => 'infoPage.store', 'info' => $infoPage->id)) }}

@include('infoPage.form')

{{ Form::close() }}
@endsection