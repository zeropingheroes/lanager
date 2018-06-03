@extends('layouts.default')

@section('title')
    @lang('title.events')
@endsection

@section('content')
    <h1>@lang('title.events')</h1>

    <script type="text/javascript">
        window.onload = function () {
            const app = new Vue({
                el: '#app'
            });
        }
    </script>
    <div id="app">
        <schedule></schedule>
    </div>
@endsection