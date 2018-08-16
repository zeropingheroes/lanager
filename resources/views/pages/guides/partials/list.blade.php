<table class="table table-striped">
    <tbody>
    @foreach($guides as $guide)
        @can('view', $guide)
            <tr>
                <td>
                    <a href="{{ route('lans.guides.show', ['lan' => $guide->lan, 'guide' => $guide, 'slug' => str_slug($guide->title) ]) }}">{{ $guide->title }}</a>
                    @canany(['update', 'delete'], $guide)
                        @if(!$guide->published)
                            <small>&mdash; @lang('title.unpublished')</small>
                        @endif
                    @endcanany
                </td>
                <td>
                    @lang('title.updated') @include('components.time-relative', ['datetime' => $guide->updated_at])
                </td>
                @canany(['update', 'delete'], $guide)
                    <td>
                        @component('components.actions-dropdown')
                            @can('update', $guide)
                                <a class="dropdown-item copy-markdown"
                                   href="#"
                                   data-clipboard-text="[{{ $guide->title }}]({{ route('lans.guides.show', ['lan' => $guide->lan, 'guide' => $guide, 'slug' => str_slug($guide->title)], false) }})">
                                    @lang('title.copy-markdown-link')
                                </a>
                                <a href="{{ route('lans.guides.edit', ['lan' => $guide->lan, 'guide' => $guide]) }}"
                                   class="dropdown-item">@lang('title.edit')</a>
                            @endcan
                            @can('delete', $guide)
                                <form action="{{ route('lans.guides.destroy', ['lan' => $guide->lan, 'guide' => $guide]) }}" method="POST">
                                    {{ method_field('DELETE') }}
                                    {{ csrf_field() }}
                                    <a class="dropdown-item" href="#" onclick="$(this).closest('form').submit();">@lang('title.delete')</a>
                                </form>
                            @endcan
                        @endcomponent
                    </td>
                @endcanany
            </tr>
        @endcan
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
