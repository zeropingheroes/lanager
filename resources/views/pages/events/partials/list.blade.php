<table class="table table-striped">
    <thead>
    <tr>
        <th>@lang('title.name')</th>
        <th>@lang('title.status')</th>
        <th>@lang('title.time')</th>
        <th>@lang('title.type')</th>
        @if( Gate::allows('update', Zeropingheroes\Lanager\Event::class) ||
             Gate::allows('destroy', Zeropingheroes\Lanager\Event::class) )
            <th>@lang('title.actions')</th>
        @endif
    </tr>
    </thead>
    <tbody>
    @foreach($events as $event)
        <tr>
            <td>
                <a href="{{ route('lans.events.show', ['lan' => $event->lan, 'event' => $event]) }}">{{ $event->name }}</a>
            </td>
            <td>
                @include('pages.events.partials.status', ['event' => $event])
            </td>
            <td>
                @include('pages.events.partials.start-and-end', ['event' => $event])
            </td>
            <td>
                @include('pages.event-types.partials.label', ['eventType' => $event->type])
            </td>
            @if( Gate::allows('update', Zeropingheroes\Lanager\Event::class) ||
                 Gate::allows('destroy', Zeropingheroes\Lanager\Event::class) )
                <td>
                    @component('components.actions-dropdown')
                        <a class="dropdown-item copy-markdown"
                           href="#"
                           data-clipboard-text="[{{ $event->name }}]({{ route('lans.events.show', ['lan' => $event->lan, 'event' => $event], false) }})">
                            @lang('title.copy-markdown-link')
                        </a>
                        <a href="{{ route('lans.events.edit', ['lan' => $event->lan, 'event' => $event->id]) }}" class="dropdown-item">@lang('title.edit')</a>
                        <form action="{{ route('lans.events.destroy', ['lan' => $event->lan, 'event' => $event->id]) }}" method="POST">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <a class="dropdown-item" href="#" onclick="$(this).closest('form').submit();">@lang('title.delete')</a>
                        </form>
                    @endcomponent
                </td>
            @endif
        </tr>
    @endforeach
    </tbody>
</table>
<script type="text/javascript">
    window.onload = function () {
        // Copy to clipboard button
        var clipboard = new Clipboard('.copy-markdown');
        clipboard.on('error', function(e) {
            console.error('Action:', e.action);
            console.error('Trigger:', e.trigger);
        });
    }
</script>