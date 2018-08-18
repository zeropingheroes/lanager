@extends('layouts.default')

@section('title')
    @lang('title.events')
@endsection

@section('content')
    @include('pages.events.partials.header-index', ['active' => 'schedule', 'lan' => $lan])
    {{ Breadcrumbs::render('lans.events.index', $lan) }}

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