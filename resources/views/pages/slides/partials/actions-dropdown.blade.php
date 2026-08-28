@component('components.actions-dropdown')
    @can('update', $slide)
        <a href="{{ route('lans.slides.edit', ['lan' => $slide->lan, 'slide' => $slide]) }}" class="dropdown-item"><i class="fa-solid fa-pen-to-square"></i> @lang('title.edit')</a>
    @endcan
    @can('delete', $slide)
        <form action="{{ route('lans.slides.destroy', ['lan' => $slide->lan, 'slide' => $slide]) }}" method="POST">
            {{ method_field('DELETE') }}
            {{ csrf_field() }}
            <a class="dropdown-item" href="#" onclick="confirmFormSubmit(event);"><i class="fa-solid fa-trash"></i> @lang('title.delete')</a>
        </form>
    @endcan
@endcomponent
