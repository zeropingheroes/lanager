<table class="table table-striped">
    <tbody>
    @foreach($events as $event)
        @can('view', $event)
            <tr>
                <td>
                    <a href="{{ route('lans.events.show', ['lan' => $event->lan, 'event' => $event]) }}">{{ $event->name }}</a>
                </td>
                <td>
                    @include('pages.events.partials.status', ['event' => $event])
                </td>
                <td>
                    @include('pages.events.partials.terse-timespan', ['start' => $event->start, 'end' => $event->end])
                </td>
                @canany(['update', 'delete'], $event)
                    <td>
                        @if($event->published)
                            <span class="badge text-bg-success"><i class="fa-solid fa-globe"></i> @lang('title.published')</span>
                        @else
                            <span class="badge text-bg-secondary"><i class="fa-solid fa-file-pen"></i> @lang('title.draft')</span>
                        @endif
                    </td>
                    <td class="text-end pe-0">
                        @include('pages.events.partials.actions-dropdown', ['event' => $event, 'lan' => $lan])
                    </td>
                @endcanany
            </tr>
        @endcan
    @endforeach
    </tbody>
</table>
