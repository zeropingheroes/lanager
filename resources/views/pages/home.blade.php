@extends('layouts.default')

@section('title', 'Home')

@section('content')
    <div class="card">
        <div class="card-body">
            <h1 class="card-title">@yield('title')</h5>
            <p class="card-text">Hello world.</p>
        </div>
    </div>
@endsection
