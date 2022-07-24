<table class="table table-striped">
    <tbody>
    @foreach($allowedIpRanges as $allowedIpRange)
        @can('view', $allowedIpRange)
            <tr>
                <td>
                    {{ $allowedIpRange->ip_range }}
                </td>
                <td>
                    {{ $allowedIpRange->description }}
                </td>
                <td>
                    @lang('title.updated') @include('components.time-relative', ['datetime' => $allowedIpRange->updated_at])
                </td>
                @canany(['update', 'delete'], $allowedIpRange)
                    <td class="text-right pr-0">
                        @include('pages.allowed-ip-ranges.partials.actions-dropdown', ['allowedIpRange' => $allowedIpRange])
                    </td>
                @endcanany
            </tr>
        @endcan
    @endforeach
    </tbody>
</table>
