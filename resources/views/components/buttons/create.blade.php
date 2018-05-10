@can('create', $item)
    @php
        $route = kebab_case(str_plural(class_basename($item)));
    @endphp
    <a href="{{ route( $route . '.create') }}" class="btn btn-primary">@lang('title.create')</a>
@endcan