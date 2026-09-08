<table class="table table-striped">
    <tbody>
    @foreach($guides as $guide)
        @can('view', $guide)
            <tr>
                <td>
                    <a href="{{ route('lans.guides.show', ['lan' => $guide->lan, 'guide' => $guide, 'slug' => \Illuminate\Support\Str::slug($guide->title) ]) }}">{{ $guide->title }}</a>
                </td>
                <td>
                    @lang('title.updated') @include('components.time-relative', ['datetime' => $guide->updated_at])
                </td>
                @canany(['update', 'delete'], $guide)
                    <td>
                        @if($guide->published)
                            <span class="badge text-bg-success"><i class="fa-solid fa-globe"></i> @lang('title.published')</span>
                        @else
                            <span class="badge text-bg-secondary"><i class="fa-solid fa-file-pen"></i> @lang('title.draft')</span>
                        @endif
                    </td>
                    <td class="text-end pe-0">
                        @include('pages.guides.partials.actions-dropdown', ['guide' => $guide])
                    </td>
                @endcanany
            </tr>
        @endcan
    @endforeach
    </tbody>
</table>
