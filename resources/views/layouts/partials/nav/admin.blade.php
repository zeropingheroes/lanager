<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="admin-menu" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="oi oi-cog" title="Cog" aria-hidden="true"></span>
        @if($errorCount)<span class="badge badge-danger align-text-top">{{ $errorCount }}</span>@endif
    </a>
    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
        <a class="dropdown-item" href="{{ route('events.index') }}">@lang('title.events')</a>
        <a class="dropdown-item" href="{{ route('pages.index') }}">@lang('title.info-pages')</a>
        <a class="dropdown-item" href="{{ route('images.index') }}">@lang('title.images')</a>
        <a class="dropdown-item" href="{{ route('role-assignments.index') }}">@lang('title.roles')</a>
        <a class="dropdown-item" href="{{ route('lans.index') }}">@lang('title.lans')</a>
        <a class="dropdown-item" href="{{ route('event-types.index') }}">@lang('title.event-types')</a>
        <a class="dropdown-item" href="{{ route('navigation-links.index') }}">@lang('title.navigation-links')</a>
        <a class="dropdown-item" href="{{ route('logs.index') }}">@lang('title.logs') @if($errorCount) <span class="badge badge-danger">{{ $errorCount }}</span>@endif</a>
    </div>
</li>