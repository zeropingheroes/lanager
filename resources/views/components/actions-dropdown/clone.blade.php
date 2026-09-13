@can('create', get_class($item))
    @php
        $route = \Illuminate\Support\Str::kebab(\Illuminate\Support\Str::plural(class_basename($item)));
    @endphp
    <a href="{{ route( $route . '.clone.create', $item->id) }}" class="dropdown-item"><i class="fa-solid fa-copy"></i> @lang('title.clone')</a>
@endcan
