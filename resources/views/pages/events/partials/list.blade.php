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
                @canany(['update', 'delete'], $event)
                    <td class="text-right pr-0">
                        @include('pages.events.partials.actions-dropdown', ['event' => $event])
                    </td>
                @endcanany
            </tr>
        @endcan
    @endforeach
    </tbody>
</table>
