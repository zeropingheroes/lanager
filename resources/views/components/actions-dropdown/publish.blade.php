@can('update', $item)
    @unless($item->published)
        <form action="{{ $route }}" method="POST">
            {{ method_field('PATCH') }}
            {{ csrf_field() }}
            <button type="submit" class="dropdown-item"><i class="fa-solid fa-globe"></i> @lang('title.publish')</button>
        </form>
    @endunless
@endcan
