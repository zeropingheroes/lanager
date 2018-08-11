<table class="table table-striped">
    <thead>
    <tr>
        <th>@lang('title.title')</th>
        <th>@lang('title.updated')</th>
        @if( Gate::allows('update', Zeropingheroes\Lanager\Guide::class) ||
             Gate::allows('destroy', Zeropingheroes\Lanager\Guide::class) )
            <th>
                @lang('title.published')
            </th>
            <th>
                @lang('title.actions')
            </th>
        @endif
    </tr>
    </thead>
    <tbody>
    @foreach($guides as $guide)
        <tr>
            <td>
                <a href="{{ route('lans.guides.show', ['lan' => $guide->lan, 'guide' => $guide, 'slug' => str_slug($guide->title) ]) }}">{{ $guide->title }}</a>
            </td>
            <td>
                @include('components.time-relative', ['datetime' => $guide->updated_at])
            </td>
            @if( Gate::allows('update', $guide) || Gate::allows('destroy', $guide) )
                <td>
                    @include('components.tick-cross', ['value' => $guide->published])
                </td>
                <td>
                    @component('components.actions-dropdown')
                        <a class="dropdown-item copy-markdown"
                           href="#"
                           data-clipboard-text="[{{ $guide->title }}]({{ route('lans.guides.show', ['lan' => $guide->lan, 'guide' => $guide, 'slug' => str_slug($guide->title)], false) }})">
                            @lang('title.copy-markdown-link')
                        </a>
                        <a href="{{ route('lans.guides.edit', ['lan' => $guide->lan, 'guide' => $guide]) }}"
                           class="dropdown-item">@lang('title.edit')</a>
                        <form action="{{ route('lans.guides.destroy', ['lan' => $guide->lan, 'guide' => $guide]) }}" method="POST">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <a class="dropdown-item" href="#" onclick="$(this).closest('form').submit();">@lang('title.delete')</a>
                        </form>
                    @endcomponent
                </td>
            @endif
        </tr>
    @endforeach
    </tbody>
</table>
<script type="text/javascript">
    window.onload = function () {
        // Copy to clipboard button
        var clipboard = new Clipboard('.copy-markdown');
        clipboard.on('error', function(e) {
            console.error('Action:', e.action);
            console.error('Trigger:', e.trigger);
        });
    }
</script>
