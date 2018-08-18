@component('components.actions-dropdown')
    @can('update', $event)
        <a class="dropdown-item copy-to-clipboard"
           href="#"
           data-clipboard-text="[{{ $event->name }}]({{ route('lans.events.show', ['lan' => $event->lan, 'event' => $event], false) }})">
            @lang('title.copy-markdown-link')
        </a>
        <a href="{{ route('lans.events.edit', ['lan' => $event->lan, 'event' => $event->id]) }}" class="dropdown-item">@lang('title.edit')</a>
    @endcan
    @can('delete', $event)
        <form action="{{ route('lans.events.destroy', ['lan' => $event->lan, 'event' => $event->id]) }}" method="POST">
            {{ method_field('DELETE') }}
            {{ csrf_field() }}
            <a class="dropdown-item" href="#" onclick="$(this).closest('form').submit();">@lang('title.delete')</a>
        </form>
    @endcan
@endcomponent