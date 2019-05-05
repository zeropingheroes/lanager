<table class="table table-striped">
    <tbody>
    @foreach($guides as $guide)
        @can('view', $guide)
            <tr>
                <td>
                    <a href="{{ route('lans.guides.show', ['lan' => $guide->lan, 'guide' => $guide, 'slug' => \Illuminate\Support\Str::slug($guide->title) ]) }}">{{ $guide->title }}</a>
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
                    <td class="text-right pr-0">
                        @include('pages.guides.partials.actions-dropdown', ['guide' => $guide])
                    </td>
                @endcanany
            </tr>
        @endcan
    @endforeach
    </tbody>
</table>
