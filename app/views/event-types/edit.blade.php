@extends('layouts.default')
@section('content')
    @include('layouts.default.title')
    @include('layouts.default.alerts')

    {{ Form::model($eventType, ['route' => ['event-types.update', $eventType->id], 'method' => 'PUT', 'class' => 'form-horizontal']) }}
    @include('event-types.partials.form')
    {{ Form::close() }}

@endsection
