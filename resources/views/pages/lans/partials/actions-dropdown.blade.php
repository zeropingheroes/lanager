@component('components.actions-dropdown')
    @can('update', $lan)
        <a href="{{ route('lans.edit', $lan) }}"
           class="dropdown-item">@lang('title.edit')</a>
    @endcan
    @can('delete', $lan)
        <form action="{{ route('lans.destroy', $lan) }}" method="POST">
            {{ method_field('DELETE') }}
            {{ csrf_field() }}
            <a class="dropdown-item" href="#" onclick="$(this).closest('form').submit();">@lang('title.delete')</a>
        </form>
    @endcan
@endcomponent