@php
    if ($user->state()) {
        $class = str_replace(' ', '-', strtolower($user->state()->status()));
    } else {
       $class = 'unknown';
    }
@endphp

<img class="avatar avatar-{{ $size }} avatar-{{ $class }}"
     src="{{ $user->avatar($size) }}"
     alt="Avatar for {{ $user->username }}">