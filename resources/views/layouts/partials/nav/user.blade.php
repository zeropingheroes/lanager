<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
       aria-haspopup="true"
       aria-expanded="false"
       id="user-menu">
        @include('pages.users.partials.avatar',['user' => Auth::user(), 'size' => 'small'])
        <span class="caret"></span>
    </a>

    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
        <li>
            <a class="dropdown-item"
               href="{{ route('users.show', Auth::user()->id) }}"
            ><i class="fa-solid fa-user"></i> @lang('title.profile')</a>
        </li>
        <li>
            <a class="dropdown-item"
               href="{{ route('api-tokens.index') }}"
            ><i class="fa-solid fa-key"></i> @lang('title.api-tokens')</a>
        </li>
        <li>
            <a class="dropdown-item" href="#"
               onclick="event.preventDefault();
                     document.getElementById('logout-form').submit();">
                <i class="fa-solid fa-right-from-bracket"></i> @lang('title.logout')
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
        </li>
    </ul>
</li>
