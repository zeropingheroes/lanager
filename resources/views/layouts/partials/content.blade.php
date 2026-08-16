<main class="flex-shrink-0">
    <div class="container">
        @yield('content-header')

        <div id="page-alerts">
            @section('content-alerts')
                @include('components.alerts.all')
            @show
        </div>
        @yield('content')
    </div>
</main>
