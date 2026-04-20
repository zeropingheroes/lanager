<main class="flex-shrink-0">
    <div class="container">
        @yield('content-header')

        @section('content-alerts')
            @include('components.alerts.all')
        @show
        @yield('content')
    </div>
</main>
