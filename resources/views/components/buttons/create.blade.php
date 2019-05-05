@can('create', $item)
    @php
        $route = \Illuminate\Support\Str::kebab(str_plural(class_basename($item)));
    @endphp
    <a href="{{ route( $route . '.create') }}" class="btn btn-primary">@lang('title.create')</a>
@endcan