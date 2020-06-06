@component('components.actions-dropdown')
    @can('update', $lanGame)
        <a href="{{ route('lans.lan-games.edit', ['lan' => $lanGame->lan, 'lan_game' => $lanGame]) }}" class="dropdown-item">@lang('title.edit')</a>
    @endcan
    @can('delete', $lanGame)
        <form action="{{ route('lans.lan-games.destroy', ['lan' => $lanGame->lan, 'lan_game' => $lanGame]) }}" method="POST" class="confirm-deletion">
            {{ method_field('DELETE') }}
            {{ csrf_field() }}
            <a class="dropdown-item" href="#" onclick="$(this).closest('form').submit();">@lang('title.delete')</a>
        </form>
    @endcan
@endcomponent
