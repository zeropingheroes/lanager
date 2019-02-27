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
            <div class="slide-content-container">
                <slides v-bind:lan_id="{{ $lan->id }}"></slides>
            </div>
            <fullscreen-button></fullscreen-button>
        </div>
    </div>
@endsection