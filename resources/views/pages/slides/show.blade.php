@extends('layouts.fullscreen')

@section('title')
    @isset($slide)
        {{ $slide->name }}
    @else
        @lang('title.slides')
    @endif
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
                @isset($slide)
                    <slides v-bind:id="{{ $slide->id }}"></slides>
                @else
                    <slides></slides>
                @endif
            </div>
            <fullscreen-button></fullscreen-button>
        </div>
    </div>
@endsection