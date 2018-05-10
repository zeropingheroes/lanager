@can('delete', $item)
    @php
        $route = kebab_case(str_plural(class_basename($item)));
    @endphp
    <form action="{{ route( $route . '.destroy', $item->id) }}" method="POST" class="inline">
        {{ method_field('DELETE') }}
        {{ csrf_field() }}
        <button type="submit" class="btn btn-danger">@lang('title.delete')</button>
    </form>
@endcan