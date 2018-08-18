<main class="container">
	@yield('content-header')

    @section('content-alerts')
        @include('components.alerts.all')
    @show
    @yield('content')
</main>