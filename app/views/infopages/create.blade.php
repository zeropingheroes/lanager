@extends('lanager-core::layouts.default')
@section('content')

<h3>Create Info Page</h3>

{{ Form::model($infoPage, array('route' => 'infoPage.store', 'info' => $infoPage->id)) }}

@include('lanager-core::infoPage.form')

{{ Form::close() }}
@endsection