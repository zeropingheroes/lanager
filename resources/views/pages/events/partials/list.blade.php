<table class="table table-striped">
    <tbody>
    @foreach($events as $event)
        @can('view', $event)
            <tr>
                <td>
                    <a href="{{ route('lans.events.show', ['lan' => $event->lan, 'event' => $event]) }}">{{ $event->name }}</a>
                    @canany(['update', 'delete'], $event)
                    @if(!$event->published)
                        <small>&mdash; @lang('title.unpublished')</small>
                    @endif
                    @endcanany
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
                @canany(['update', 'delete'], $event)
                    <td>
                        @component('components.actions-dropdown')
                            @can('update', $event)
                                <a class="dropdown-item copy-markdown"
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
                    </td>
                @endcanany
            </tr>
        @endcan
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