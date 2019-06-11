<table class="table table-striped">
    <thead>
    <tr>
        <th>@lang('title.name')</th>
        <th>@lang('title.content')</th>
        <th>@lang('title.position')</th>
        <th>@lang('title.duration')</th>
        <th>@lang('title.start')</th>
        <th>@lang('title.end')</th>
        <th>@lang('title.updated')</th>
        <th>@lang('title.published')</th>
        <th>@lang('title.actions')</th>
    </tr>
    </thead>
    <tbody>
    @foreach($slides as $slide)
        @can('view', $slide)
                <tr>
                    <td>
                        <a href="{{ route('lans.slides.show', ['lan' => $lan, 'slide' => $slide]) }}">{{ $slide->name }}</a>
                        @canany(['update', 'delete'], $slide)
                        @if(!$slide->published)
                            <small>&mdash; @lang('title.unpublished')</small>
                        @endif
                        @endcanany
                    </td>
                    <td>
                        {{ \Illuminate\Support\Str::limit($slide->content, 16) }}
                    </td>
                    <td>
                        {{ $slide->position }}
                    </td>
                    <td>
                        {{ \Carbon\CarbonInterval::seconds($slide->duration)->cascade()->forHumans() }}
                    </td>
                    <td>
                        {{ $slide->start ?? "Not Set" }}
                    </td>
                    <td>
                        {{ $slide->end ?? "Not Set" }}
                    </td>
                    <td>
                        @lang('title.updated') @include('components.time-relative', ['datetime' => $slide->updated_at])
                    </td>
                    <td>
                        {{ $slide->published ? 'Yes' : 'No' }}
                    </td>
                    @canany(['update', 'delete'], $slide)
                        <td class="">
                            @include('pages.slides.partials.actions-dropdown', ['slide' => $slide])
                        </td>
                    @endcanany
                </tr>
        @endcan
    @endforeach
    </tbody>
</table>
