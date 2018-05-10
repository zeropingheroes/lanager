@can('update', $item)
    @php
        $route = kebab_case(str_plural(class_basename($item)));
    @endphp
    <a href="{{ route( $route . '.edit', $item->id) }}" class="dropdown-item">@lang('title.edit')</a>
@endcan