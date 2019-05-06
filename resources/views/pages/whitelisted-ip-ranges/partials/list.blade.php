<table class="table table-striped">
    <tbody>
    @foreach($whitelistedIpRanges as $whitelistedIpRange)
        @can('view', $whitelistedIpRange)
            <tr>
                <td>
                    {{ $whitelistedIpRange->ip_range }}
                </td>
                <td>
                    {{ $whitelistedIpRange->description }}
                </td>
                <td>
                    @lang('title.updated') @include('components.time-relative', ['datetime' => $whitelistedIpRange->updated_at])
                </td>
                @canany(['update', 'delete'], $whitelistedIpRange)
                    <td class="text-right pr-0">
                        @include('pages.whitelisted-ip-ranges.partials.actions-dropdown', ['whitelistedIpRange' => $whitelistedIpRange])
                    </td>
                @endcanany
            </tr>
        @endcan
    @endforeach
    </tbody>
</table>
