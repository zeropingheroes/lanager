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
                <a href="{{ route('events.show', $event->id) }}">{{ $event->name }}</a>
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
                        @include('components.actions-dropdown.edit', ['item' => $event])
                        @include('components.actions-dropdown.delete', ['item' => $event])
                    @endcomponent
                </td>
            @endif
        </tr>
    @endforeach
    </tbody>
</table>
