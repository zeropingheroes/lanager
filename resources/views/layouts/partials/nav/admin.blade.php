<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle"
       href="#"
       role="button"
       data-bs-toggle="dropdown"
       aria-haspopup="true"
       aria-expanded="false"
       id="admin-menu"
    >
        <i class="fa-solid fa-gear"></i>
    </a>
    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
        <li><a class="dropdown-item" href="{{ route('lans.index') }}"><i class="fa-solid fa-network-wired"></i> @lang('title.lans')</a></li>
        <li><a class="dropdown-item" href="{{ route('role-assignments.index') }}"><i class="fa-solid fa-user-shield"></i> @lang('title.role-assignments')</a>
        </li>
        <li><a class="dropdown-item" href="{{ route('navigation-links.index') }}"><i class="fa-solid fa-compass"></i> @lang('title.navigation')</a></li>
        <li><a class="dropdown-item" href="{{ route('achievements.index') }}"><i class="fa-solid fa-trophy"></i> @lang('title.achievements')</a></li>
        <li><a class="dropdown-item" href="{{ route('venues.index') }}"><i class="fa-solid fa-location-dot"></i> @lang('title.venues')</a></li>
    </ul>
</li>
