@component('components.actions-dropdown')
    @can('update', $lan)
        <a href="{{ route('lans.edit', $lan) }}"
           class="dropdown-item"><i class="fa-solid fa-pen-to-square"></i> @lang('title.edit')</a>
    @endcan
    @include('components.actions-dropdown.publish', ['item' => $lan, 'route' => route('lans.publish', $lan)])
    @include('components.actions-dropdown.unpublish', ['item' => $lan, 'route' => route('lans.unpublish', $lan)])
    @can('create', \Zeropingheroes\Lanager\Models\Lan::class)
        <a href="{{ route('lans.clone.create', $lan) }}"
           class="dropdown-item"><i class="fa-solid fa-copy"></i> @lang('title.clone')</a>
    @endcan
    @can('delete', $lan)
        <form action="{{ route('lans.destroy', $lan) }}" method="POST">
            {{ method_field('DELETE') }}
            {{ csrf_field() }}
            <a class="dropdown-item" href="#" onclick="confirmFormSubmit(event);"><i class="fa-solid fa-trash"></i> @lang('title.delete')</a>
        </form>
    @endcan
@endcomponent
