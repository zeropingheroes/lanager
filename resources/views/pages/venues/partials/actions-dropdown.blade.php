@component('components.actions-dropdown')
    @can('update', $venue)
        <a href="{{ route('venues.edit', $venue) }}" class="dropdown-item">@lang('title.edit')</a>
    @endcan
    @can('delete', $venue)
        <form action="{{ route('venues.destroy', $venue) }}" method="POST" class="confirm-deletion">
            {{ method_field('DELETE') }}
            {{ csrf_field() }}
            <a class="dropdown-item" href="#" onclick="$(this).closest('form').submit();">@lang('title.delete')</a>
        </form>
    @endcan
@endcomponent