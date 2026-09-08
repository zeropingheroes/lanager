<table class="table table-striped align-middle">
    <thead>
    <tr>
        <th>@lang('title.name')</th>
        <th>@lang('title.position')</th>
        <th>@lang('title.duration')</th>
        <th>@lang('title.active')</th>
        <th>@lang('title.updated')</th>
        <th>@lang('title.visibility')</th>
        <th>@lang('title.actions')</th>
    </tr>
    </thead>
    <tbody>
    @foreach($slides as $slide)
        @can('view', $slide)
                <tr>
                    <td>
                        <a href="{{ route('lans.slides.show', ['lan' => $lan, 'slide' => $slide]) }}">{{ $slide->name }}</a>
                    </td>
                    <td>
                        {{ $slide->position }}
                    </td>
                    <td>
                        {{ \Carbon\CarbonInterval::seconds($slide->duration)->cascade()->forHumans() }}
                    </td>
                    <td>
                    @isset($slide->start)
                        @include('pages.slides.partials.start-and-end', ['slide' => $slide])
                        @endisset
                    </td>
                    <td>
                        @include('components.time-relative', ['datetime' => $slide->updated_at])
                    </td>
                    <td>
                        @if($slide->published)
                            <span class="badge text-bg-success"><i class="fa-solid fa-globe"></i> @lang('title.published')</span>
                        @else
                            <span class="badge text-bg-secondary"><i class="fa-solid fa-file-pen"></i> @lang('title.draft')</span>
                        @endif
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
