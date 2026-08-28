<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link {{ Request::route()->getName() == 'lans.lan-games.index' ? 'active' : '' }}"
           href="{{ route('lans.lan-games.index', $lan) }}">
            <i class="fa-solid fa-gamepad"></i> @lang('title.games')
        </a>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle {{ Request::route()->getName() == 'lans.events.index' ? 'active' : '' }}"
           data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
            <i class="fa-solid fa-calendar"></i> @lang('title.events')
        </a>
        <div class="dropdown-menu">
            <a class="dropdown-item" href="{{ route('lans.events.index', $lan) }}"><i class="fa-solid fa-list"></i> @lang('title.list')</a>
            <a class="dropdown-item"
               href="{{ route('lans.events.index', ['lan' => $lan, 'schedule']) }}"><i class="fa-solid fa-calendar-days"></i> @lang('title.schedule')</a>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ Request::route()->getName() == 'lans.guides.index' ? 'active' : '' }}"
           href="{{ route('lans.guides.index', $lan) }}">
            <i class="fa-solid fa-book"></i> @lang('title.guides')
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ Request::route()->getName() == 'lans.attendees.index' ? 'active' : '' }}"
           href="{{ route('lans.attendees.index', $lan) }}">
            <i class="fa-solid fa-users"></i> @lang('title.attendees')
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ Request::route()->getName() == 'lans.user-achievements.index' ? 'active' : '' }}"
           href="{{ route('lans.user-achievements.index', $lan) }}">
            <i class="fa-solid fa-trophy"></i> @lang('title.achievements')
        </a>
    </li>
    @can('create', \Zeropingheroes\Lanager\Models\Slide::class)
        <li class="nav-item">
            <a class="nav-link {{ Request::route()->getName() == 'lans.slides.index' ? 'active' : '' }}"
               href="{{ route('lans.slides.index', $lan) }}">
                <i class="fa-solid fa-images"></i> @lang('title.slides')
            </a>
        </li>
    @endcan
    @can('index', \Zeropingheroes\Lanager\Models\DiscordChannelWebhook::class)
        <li class="nav-item">
            <a class="nav-link {{ str_starts_with(Request::route()->getName(), 'lans.discord-channel-webhooks') ? 'active' : '' }}"
               href="{{ route('lans.discord-channel-webhooks.index', $lan) }}">
                <i class="fa-solid fa-hashtag"></i> @lang('title.channels')
            </a>
        </li>
    @endcan
</ul>
