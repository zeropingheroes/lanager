@if (Auth::user()->hasRole('super admin', 'admin'))
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="glyphicon glyphicon-cog" aria-hidden="true"></span> @lang('title.admin')
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="#">@lang('title.role-assignments')</a>
            <a class="dropdown-item" href="#">@lang('title.events')</a>
            <a class="dropdown-item" href="#">@lang('title.info')</a>
            <a class="dropdown-item" href="#">@lang('title.achievements')</a>
            <a class="dropdown-item" href="#">@lang('title.logs')</a>
        </div>
    </li>
@endif