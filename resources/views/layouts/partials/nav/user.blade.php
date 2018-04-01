<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        {{ Auth::user()->username }} <span class="caret"></span>
    </a>

    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
        <a class="dropdown-item" href="#">@lang('title.profile')</a>
        <a class="dropdown-item" href="#">@lang('title.api')</a>
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