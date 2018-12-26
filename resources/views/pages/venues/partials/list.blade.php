<table class="table table-striped">
    <tbody>
    @foreach($venues as $venue)
        @can('view', $venue)
            <tr>
                <td>
                    <a href="{{ route('venues.show', $venue) }}">{{ $venue->name }}</a>
                </td>
                <td>
                    {{ $venue->street_address }}</a>
                </td>
                <td>
                    @lang('title.updated') @include('components.time-relative', ['datetime' => $venue->updated_at])
                </td>
                @canany(['update', 'delete'], $venue)
                    <td class="text-right pr-0">
                        @include('pages.venues.partials.actions-dropdown', ['venue' => $venue])
                    </td>
                @endcanany
            </tr>
        @endcan
    @endforeach
    </tbody>
</table>
