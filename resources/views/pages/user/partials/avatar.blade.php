<img class="avatar avatar-{{ $size }} avatar-{{ str_replace(' ', '-', strtolower($user->state->status())) }}"
     src="{{ $user->avatar($size) }}"
     alt="Avatar for {{ $user->username }}">