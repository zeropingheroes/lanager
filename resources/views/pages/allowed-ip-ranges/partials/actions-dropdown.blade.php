@component('components.actions-dropdown')
    @can('update', $allowedIpRange)
        <a href="{{ route('allowed-ip-ranges.edit', $allowedIpRange) }}" class="dropdown-item"><i class="fa-solid fa-pen-to-square"></i> @lang('title.edit')</a>
    @endcan
    @can('delete', $allowedIpRange)
        <form action="{{ route('allowed-ip-ranges.destroy', $allowedIpRange) }}" method="POST">
            {{ method_field('DELETE') }}
            {{ csrf_field() }}
            <a class="dropdown-item" href="#" onclick="confirmFormSubmit(event);"><i class="fa-solid fa-trash"></i> @lang('title.delete')</a>
        </form>
    @endcan
@endcomponent
