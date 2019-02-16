@extends('layouts.fullscreen')

@section('title')
    @lang('title.slides')
@endsection

@section('content')
    <script>
        window.addEventListener('load', function() {
            const app = new Vue({
                el: '#app'
            });
        });
    </script>
    <div class="container-center-flex">
        <div id="app" class="container-slides-1080">
           <div class="slide-header">
                <h1><a href="{{ url('/') }}">{{ request()->getHost() }}</a></h1>
            </div>
            <div class="slide-content-container">
                <slides></slides>
            </div>
            <fullscreen-button></fullscreen-button>
        </div>
    </div>
@endsection