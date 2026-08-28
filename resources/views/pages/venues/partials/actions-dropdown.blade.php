@component('components.actions-dropdown')
    @can('update', $venue)
        <a href="{{ route('venues.edit', $venue) }}" class="dropdown-item"><i class="fa-solid fa-pen-to-square"></i> @lang('title.edit')</a>
    @endcan
    @can('delete', $venue)
        <form action="{{ route('venues.destroy', $venue) }}" method="POST">
            {{ method_field('DELETE') }}
            {{ csrf_field() }}
            <a class="dropdown-item" href="#" onclick="confirmFormSubmit(event);"><i class="fa-solid fa-trash"></i> @lang('title.delete')</a>
        </form>
    @endcan
@endcomponent
