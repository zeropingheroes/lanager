@component('components.actions-dropdown')
    @can('update', $lanGame)
        <a href="{{ route('lan-games.edit', $lanGame) }}" class="dropdown-item">@lang('title.edit')</a>
    @endcan
    @can('delete', $lanGame)
        <form action="{{ route('lan-games.destroy', $lanGame) }}" method="POST" class="confirm-deletion">
            {{ method_field('DELETE') }}
            {{ csrf_field() }}
            <a class="dropdown-item" href="#" onclick="$(this).closest('form').submit();">@lang('title.delete')</a>
        </form>
    @endcan
@endcomponent