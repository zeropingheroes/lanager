@extends('layouts.content-only')

@section('title')
    @lang('title.slides')
@endsection

@section('content')
    @vite(['resources/js/pages/slides.js'])
    <div id="app">
        <slides v-bind:lan_id="{{ $lan->id }}"></slides>
        <fullscreen-button></fullscreen-button>
    </div>
@endsection
