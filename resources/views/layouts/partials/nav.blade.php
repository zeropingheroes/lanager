<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <a class="navbar-brand" href="{{ url('/') }}">
        <img src="/img/brand.svg" width="30" height="30" class="d-inline-block align-top" alt="">
        {{ config('app.name') }}
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="@lang('phrase.toggle-navigation')">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        @include('layouts.partials.nav.primary')
        <ul class="nav navbar-nav navbar-right">
            <!-- Authentication Links -->
            @guest
                @include('layouts.partials.nav.guest')
            @else

                @if (Auth::user()->hasRole('super-admin') || Auth::user()->hasRole('admin'))
                    @include('layouts.partials.nav.admin')
                @endif
                @include('layouts.partials.nav.user')
            @endguest
        </ul>
    </div>
</nav>