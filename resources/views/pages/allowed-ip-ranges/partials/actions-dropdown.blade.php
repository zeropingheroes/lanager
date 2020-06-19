@component('components.actions-dropdown')
    @can('update', $allowedIpRange)
        <a href="{{ route('allowed-ip-ranges.edit', $allowedIpRange) }}" class="dropdown-item">@lang('title.edit')</a>
    @endcan
    @can('delete', $allowedIpRange)
        <form action="{{ route('allowed-ip-ranges.destroy', $allowedIpRange) }}" method="POST" class="confirm-deletion">
            {{ method_field('DELETE') }}
            {{ csrf_field() }}
            <a class="dropdown-item" href="#" onclick="$(this).closest('form').submit();">@lang('title.delete')</a>
        </form>
    @endcan
@endcomponent
