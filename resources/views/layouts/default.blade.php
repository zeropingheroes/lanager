@include('layouts.partials.header')
@if( ! request()->has('fullscreen') )
    @include('layouts.partials.nav')
    @include('layouts.partials.content')
    @include('layouts.partials.footer')
@else
    <main class="container-fullscreen">
        @yield('content-header')
        @yield('content')
    </main>
@endif