@extends('layouts.default')
@section('content')
    @include('layouts.default.title')
    @include('layouts.default.alerts')

    {{ Form::model($achievement, ['route' => ['achievements.update', $achievement->id], 'method' => 'PUT', 'class' => 'form-horizontal']) }}
    @include('achievements.partials.form')
    {{ Form::close() }}
@endsection