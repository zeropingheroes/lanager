@extends('layouts.content-only')

@section('title')
    @lang('title.events')
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
        <events></events>
    </div>
@endsection