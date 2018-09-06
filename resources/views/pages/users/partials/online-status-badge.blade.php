@php($activeSession = $user->steamAppSessions()->active()->first())

@if($activeSession && $activeSession->app->exists)
    <span class="badge badge-success">@lang('phrase.status-in-game')</span>
@elseif($user->steamMetadata->exists)
    @switch($user->steamMetadata->status->name)
        @case('online')
        @case('looking-to-trade')
        @case('looking-to-play')
            @php ($class = 'info')
        @break

        @case('busy')
            @php ($class = 'danger')
        @break

        @case('away')
        @case('snooze')
            @php ($class = 'warning')
        @break

        @default
            @php ($class = 'secondary')
    @endswitch
    <span class="badge badge-{{ $class }}">{{ $user->steamMetadata->status->display_name }}</span>
@else
    <span class="badge badge-secondary">@lang('phrase.status-unknown')</span>
@endif