@php
    $statusText = __('phrase.status-'.str_replace(' ', '-', strtolower($user->state->status())));
@endphp
@switch(strtoupper($user->state->status()))
    @case('ONLINE')
    <span class="badge badge-info">{{ $statusText }}</span>
    @break

    @case('IN GAME')
    <span class="badge badge-success">{{ $statusText }}</span>
    @break

    @case('BUSY')
    <span class="badge badge-danger">{{ $statusText }}</span>
    @break

    @case('AWAY')
    @case('SNOOZE')
    <span class="badge badge-warning">{{ $statusText }}</span>
    @break

    @case('LOOKING TO TRADE')
    @case('LOOKING TO PLAY')
    <span class="badge badge-info">{{ $statusText }}</span>
    @break

    @case('OFFLINE')
    <span class="badge badge-secondary">{{ $statusText }}</span>
    @break

    @default
    <span class="badge badge-secondary">{{ $statusText }}</span>
@endswitch