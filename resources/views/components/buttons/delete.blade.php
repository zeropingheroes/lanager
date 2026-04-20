@can('delete', $item)
    @php
        $route = \Illuminate\Support\Str::kebab(\Illuminate\Support\Str::plural(class_basename($item)));
    @endphp
    <form action="{{ route( $route . '.destroy', $item->id) }}" method="POST" class="d-inline">
        {{ method_field('DELETE') }}
        {{ csrf_field() }}
        <button type="submit" class="btn btn-danger" onclick="submitDeletionForm(event)">@lang('title.delete')</button>
    </form>
@endcan
