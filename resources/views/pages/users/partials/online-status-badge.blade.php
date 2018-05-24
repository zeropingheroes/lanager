@if($user->state)
    @if($user->state->steam_app_id)
        <span class="badge badge-success">@lang('phrase.status-in-game')</span>
    @else
        @php
            $status = __('phrase.status-'.kebab_case($user->state->status->name));
        @endphp
        @switch(kebab_case($user->state->status->name))
            @case('online')
            @case('looking-to-trade')
            @case('looking-to-play')
                <span class="badge badge-info">{{ $status }}</span>
            @break

            @case('busy')
                <span class="badge badge-danger">{{ $status }}</span>
            @break

            @case('away')
            @case('snooze')
                <span class="badge badge-warning">{{ $status }}</span>
            @break

            @default
                <span class="badge badge-secondary">{{ $status }}</span>
        @endswitch
    @endif
@else
    <span class="badge badge-secondary">@lang('phrase.status-unknown')</span>
@endif