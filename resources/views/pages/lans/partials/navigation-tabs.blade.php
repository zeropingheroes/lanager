<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link {{ Request::route()->getName() == 'lans.lan-games.index' ? 'active' : '' }}"
           href="{{ route('lans.lan-games.index', $lan) }}">
            @lang('title.games') <span class="badge">{{ $lan->games->count() }}</span>
        </a>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle {{ Request::route()->getName() == 'lans.events.index' ? 'active' : '' }}"
           data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
            @lang('title.events') <span
                class="badge">{{ $lan->events()->where('published', '=', '1')->count() }}</span>
        </a>
        <div class="dropdown-menu">
            <a class="dropdown-item" href="{{ route('lans.events.index', $lan) }}">@lang('title.list')</a>
            <a class="dropdown-item"
               href="{{ route('lans.events.index', ['lan' => $lan, 'schedule']) }}">@lang('title.schedule')</a>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ Request::route()->getName() == 'lans.guides.index' ? 'active' : '' }}"
           href="{{ route('lans.guides.index', $lan) }}">
            @lang('title.guides') <span class="badge">{{ $lan->guides()->where('published', '=', '1')->count() }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ Request::route()->getName() == 'lans.attendees.index' ? 'active' : '' }}"
           href="{{ route('lans.attendees.index', $lan) }}">
            @lang('title.attendees') <span class="badge">{{ $lan->users->count() }}</span>
        </a>
    </li>
    @can('create', \Zeropingheroes\Lanager\Models\Slide::class)
        <li class="nav-item">
            <a class="nav-link {{ Request::route()->getName() == 'lans.slides.index' ? 'active' : '' }}"
               href="{{ route('lans.slides.index', $lan) }}">
                @lang('title.slides') <span
                    class="badge">{{ $lan->slides()->where('published', '=', '1')->count() }}</span>
            </a>
        </li>
    @endcan
</ul>
