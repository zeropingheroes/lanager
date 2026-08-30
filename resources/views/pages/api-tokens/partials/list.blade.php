<table class="table table-striped align-middle mb-4">
    <thead>
    <tr>
        <th>@lang('title.name')</th>
        <th>@lang('title.created')</th>
        <th>@lang('title.last-used')</th>
        <th>@lang('title.actions')</th>
    </tr>
    </thead>
    <tbody>
    @forelse($tokens as $token)
        <tr>
            <td>{{ $token->name }}</td>
            <td>@include('components.time-relative', ['datetime' => $token->created_at])</td>
            <td>
                @if($token->last_used_at)
                    @include('components.time-relative', ['datetime' => $token->last_used_at])
                @else
                    @lang('title.never')
                @endif
            </td>
            <td>
                @include('pages.api-tokens.partials.action-delete', ['token' => $token])
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="4">@lang('phrase.no-api-tokens-yet')</td>
        </tr>
    @endforelse
    </tbody>
</table>
