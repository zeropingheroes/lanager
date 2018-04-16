@if($user->state())
    {{ $user->state()->status() }}
@else
    Status unknown
@endif