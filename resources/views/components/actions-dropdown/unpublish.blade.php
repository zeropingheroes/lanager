@can('update', $item)
    @if($item->published)
        <form action="{{ $route }}" method="POST">
            {{ method_field('PATCH') }}
            {{ csrf_field() }}
            <button type="submit" class="dropdown-item"><i class="fa-solid fa-file-pen"></i> @lang('title.unpublish')</button>
        </form>
    @endif
@endcan
