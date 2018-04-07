<ul class="navbar-nav mr-auto">
    <li class="nav-item">
        <a class="nav-link" href="#">@lang('title.events')</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('users.index') }}">@lang('title.users')</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#">@lang('title.games')</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#">@lang('title.achievements')</a>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            @lang('title.info')
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="#">Page 1</a>
            <a class="dropdown-item" href="#">Page 2</a>
        </div>
    </li>
</ul>