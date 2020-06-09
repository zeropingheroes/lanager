@component('components.actions-dropdown')
    @can('update', $whitelistedIpRange)
        <a href="{{ route('whitelisted-ip-ranges.edit', $whitelistedIpRange) }}" class="dropdown-item">@lang('title.edit')</a>
    @endcan
    @can('delete', $whitelistedIpRange)
        <form action="{{ route('whitelisted-ip-ranges.destroy', $whitelistedIpRange) }}" method="POST" class="confirm-deletion">
            {{ method_field('DELETE') }}
            {{ csrf_field() }}
            <a class="dropdown-item" href="#" onclick="$(this).closest('form').submit();">@lang('title.delete')</a>
        </form>
    @endcan
@endcomponent
