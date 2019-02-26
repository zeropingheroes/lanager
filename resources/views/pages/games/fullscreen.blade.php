@extends('layouts.fullscreen')

@section('title')
    @lang('title.games')
@endsection

@section('content')
    <script>
        window.addEventListener('load', function() {
            const app = new Vue({
                el: '#app'
            });
        });
    </script>
    <div id="app" class="tv">
        <active-games></active-games>
    </div>
@endsection