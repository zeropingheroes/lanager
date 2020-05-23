@can('update', $item)
    @php
        $route = \Illuminate\Support\Str::kebab(\Illuminate\Support\Str::plural(class_basename($item)));
    @endphp
    <a href="{{ route( $route . '.edit', $item->id) }}" class="dropdown-item">@lang('title.edit')</a>
@endcan
