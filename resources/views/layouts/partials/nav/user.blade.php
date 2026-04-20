<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
       aria-haspopup="true"
       aria-expanded="false">
        @include('pages.users.partials.avatar',['user' => Auth::user(), 'size' => 'small'])
        <span class="caret"></span>
    </a>

    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
        <li>
            <a class="dropdown-item"
               href="{{ route('users.show', Auth::user()->id) }}"
            >@lang('title.profile')</a>
        </li>
        <li>
            <a class="dropdown-item" href="#"
               onclick="event.preventDefault();
                     document.getElementById('logout-form').submit();">
                @lang('title.logout')
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
        </li>
    </ul>
</li>
