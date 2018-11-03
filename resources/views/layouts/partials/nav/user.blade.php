<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true"
       aria-expanded="false">
        @include('pages.users.partials.avatar',['user' => Auth::user(), 'size' => 'small'])
        <span class="caret"></span>
    </a>

    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
        <a class="dropdown-item" href="{{ route('users.show', Auth::user()->id) }}">@lang('title.profile')</a>
        <a class="dropdown-item" href="#"
           onclick="event.preventDefault();
                     document.getElementById('logout-form').submit();">
            @lang('title.logout')
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
    </div>
</li>