@component('components.actions-dropdown')
    @can('update', $guide)
        <a class="dropdown-item copy-to-clipboard"
           href="#"
           data-clipboard-text="[{{ $guide->title }}]({{ route('lans.guides.show', ['lan' => $guide->lan, 'guide' => $guide, 'slug' => \Illuminate\Support\Str::slug($guide->title)], false) }})">
            @lang('title.copy-markdown-link')
        </a>
        <a href="{{ route('lans.guides.edit', ['lan' => $guide->lan, 'guide' => $guide]) }}"
           class="dropdown-item">@lang('title.edit')</a>
    @endcan
    @can('delete', $guide)
        <form action="{{ route('lans.guides.destroy', ['lan' => $guide->lan, 'guide' => $guide]) }}" method="POST" class="confirm-deletion">
            {{ method_field('DELETE') }}
            {{ csrf_field() }}
            <a class="dropdown-item" href="#" onclick="$(this).closest('form').submit();">@lang('title.delete')</a>
        </form>
    @endcan
@endcomponent