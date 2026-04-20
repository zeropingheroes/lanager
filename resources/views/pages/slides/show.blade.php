@extends('layouts.content-only')

@section('title')
    {{ $slide->name }}
@endsection

@section('content')
    @vite(['resources/js/pages/slides.js'])
    <div id="app">
        <slides-single v-bind:lan_id="{{ $slide->lan->id }}" v-bind:id="{{ $slide->id }}"></slides-single>
        <fullscreen-button></fullscreen-button>
    </div>
@endsection
