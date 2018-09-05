<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="admin-menu" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="oi oi-cog" title="Cog" aria-hidden="true"></span>
        @if($errorCount)<span class="badge badge-danger align-text-top">{{ $errorCount }}</span>@endif
    </a>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
        <a class="dropdown-item" href="{{ route('dashboard') }}" target="_blank">@lang('title.dashboard')</a>
        <a class="dropdown-item" href="{{ route('role-assignments.index') }}">@lang('title.role-assignments')</a>
        <a class="dropdown-item" href="{{ route('navigation-links.index') }}">@lang('title.navigation')</a>
        <a class="dropdown-item" href="{{ route('achievements.index') }}">@lang('title.achievements')</a>
        <a class="dropdown-item" href="{{ route('logs.index') }}">@lang('title.logs') @if($errorCount) <span class="badge badge-danger">{{ $errorCount }}</span>@endif</a>
    </div>
</li>