@can('create', $item)
    @php
        $route = \Illuminate\Support\Str::kebab(\Illuminate\Support\Str::plural(class_basename($item)));
    @endphp
    <a href="{{ route( $route . '.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> @lang('title.create')</a>
@endcan
