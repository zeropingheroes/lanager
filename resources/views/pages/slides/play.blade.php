@extends('layouts.content-only')

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
    <div class="center-contents-horizontally">
        <div id="app" class="container-1920x1080">
            <div class="center-contents-horizontally">
                <slides v-bind:lan_id="{{ $lan->id }}"></slides>
            </div>
            <fullscreen-button></fullscreen-button>
        </div>
    </div>
@endsection