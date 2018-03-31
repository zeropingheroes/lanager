@extends('layouts.default')
@section('content')
    @include('layouts.default.title')
    @include('layouts.default.alerts')
    {{ $description }}
@endsection