@component('components.actions-dropdown')
    @can('update', $slide)
        <a href="{{ route('lans.slides.edit', ['lan' => $slide->lan, 'slide' => $slide]) }}" class="dropdown-item"><i class="fa-solid fa-pen-to-square"></i> @lang('title.edit')</a>
    @endcan
    @can('create', \Zeropingheroes\Lanager\Models\Slide::class)
        <a href="{{ route('lans.slides.clone.create', ['lan' => $slide->lan, 'slide' => $slide]) }}" class="dropdown-item"><i class="fa-solid fa-copy"></i> @lang('title.clone')</a>
    @endcan
    @include('components.actions-dropdown.publish', ['item' => $slide, 'route' => route('lans.slides.publish', ['lan' => $slide->lan, 'slide' => $slide])])
    @include('components.actions-dropdown.unpublish', ['item' => $slide, 'route' => route('lans.slides.unpublish', ['lan' => $slide->lan, 'slide' => $slide])])
    @can('delete', $slide)
        <form action="{{ route('lans.slides.destroy', ['lan' => $slide->lan, 'slide' => $slide]) }}" method="POST">
            {{ method_field('DELETE') }}
            {{ csrf_field() }}
            <a class="dropdown-item" href="#" onclick="confirmFormSubmit(event);"><i class="fa-solid fa-trash"></i> @lang('title.delete')</a>
        </form>
    @endcan
@endcomponent
