@can('update', $item)
    @php
        $route = \Illuminate\Support\Str::kebab(str_plural(class_basename($item)));
    @endphp
    <a href="{{ route( $route . '.edit', $item->id) }}" class="btn btn-primary">@lang('title.edit')</a>
@endcan