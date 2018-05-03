@switch(strtoupper($user->state->status()))
    @case('ONLINE')
    <span class="badge badge-info">{{ $user->state->status() }}</span>
    @break

    @case('IN GAME')
    <span class="badge badge-success">{{ $user->state->status() }}</span>
    @break

    @case('BUSY')
    <span class="badge badge-danger">{{ $user->state->status() }}</span>
    @break

    @case('AWAY')
    @case('SNOOZE')
    <span class="badge badge-warning">{{ $user->state->status() }}</span>
    @break

    @case('LOOKING TO TRADE')
    @case('LOOKING TO PLAY')
    <span class="badge badge-info">{{ $user->state->status() }}</span>
    @break

    @case('OFFLINE')
    <span class="badge badge-secondary">{{ $user->state->status() }}</span>
    @break

    @default
    <span class="badge badge-secondary">{{ $user->state->status() }}</span>
@endswitch