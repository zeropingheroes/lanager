@extends('layouts.content-only')

@section('title')
    {{ $slide->name }}
@endsection

@section('content')
    <script>
        window.addEventListener('load', function() {
            const app = new Vue({
                el: '#app'
            });
        });
    </script>
    <div id="app" class="container-1920x1080 tv">
        <slides-single v-bind:lan_id="{{ $slide->lan->id }}" v-bind:id="{{ $slide->id }}"></slides-single>
        <fullscreen-button></fullscreen-button>
    </div>
@endsection