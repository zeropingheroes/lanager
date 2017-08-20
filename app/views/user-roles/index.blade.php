@extends('layouts.default')
@section('content')
    @include('layouts.default.title')
    @include('layouts.default.alerts')

    @include('user-roles.partials.list')

    @include('buttons.create', ['resource' => 'user-roles'])

@endsection
