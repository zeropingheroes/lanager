<div class="row align-items-center">
    <div class="col">
        <h1>@lang('title.events')</h1>
    </div>
    @can('create', \Zeropingheroes\Lanager\Event::class)
        <div class="col-auto text-right pr-0">
            <a href="{{ route( 'lans.events.create', $lan) }}" class="btn btn-primary" title="@lang('title.create')"><span class="oi oi-plus"></span></a>
        </div>
    @endcan
    <div class="col-auto text-right">
        <div class="dropdown show">
            <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="eventsDisplayDropdown" data-toggle="dropdown"
               aria-haspopup="true" aria-expanded="false">
                @switch($active)
                    @case('list')
                        @lang('title.list')
                        @break
                    @case('schedule')
                        @lang('title.schedule')
                        @break
                @endswitch
            </a>
            <div class="dropdown-menu" aria-labelledby="eventsDisplayDropdown">
                <a class="dropdown-item @if($active == 'list') active @endif" href="{{ route('lans.events.index', ['lan' => $lan]) }}">@lang('title.list')</a>
                <a class="dropdown-item @if($active == 'schedule') active @endif" href="{{ route('lans.events.index', ['lan' => $lan, 'schedule']) }}">@lang('title.schedule')</a>
                <a class="dropdown-item" href="{{ route('lans.events.index', ['lan' => $lan, 'fullscreen']) }}" target="_blank">@lang('title.fullscreen')</a>
            </div>
        </div>
    </div>
</div>
