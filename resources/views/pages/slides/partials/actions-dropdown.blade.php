@component('components.actions-dropdown')
    @can('update', $slide)
        <a href="{{ route('lans.slides.edit', ['lan' => $slide->lan, 'slide' => $slide]) }}" class="dropdown-item">@lang('title.edit')</a>
    @endcan
    @can('delete', $slide)
        <form action="{{ route('lans.slides.destroy', ['lan' => $slide->lan, 'slide' => $slide]) }}" method="POST" class="confirm-deletion">
            {{ method_field('DELETE') }}
            {{ csrf_field() }}
            <a class="dropdown-item" href="#" onclick="$(this).closest('form').submit();">@lang('title.delete')</a>
        </form>
    @endcan
@endcomponent