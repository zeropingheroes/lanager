@can('delete', $item)
    @php
        $route = \Illuminate\Support\Str::kebab(\Illuminate\Support\Str::plural(class_basename($item)));
    @endphp
    <form action="{{ route( $route . '.destroy', $item->id) }}" method="POST">
        {{ method_field('DELETE') }}
        {{ csrf_field() }}
        <a class="dropdown-item" href="#" onclick="confirmFormSubmit(event)">@lang('title.delete')</a>
    </form>
@endcan
