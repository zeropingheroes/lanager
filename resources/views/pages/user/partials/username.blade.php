@if($user->id && $user->username)
    <a href="{{ route('users.show', $user->id) }}">{{ $user->username }}</a>
@endif