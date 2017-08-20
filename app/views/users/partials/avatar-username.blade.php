<a href="{{ URL::route('users.show', $user->id) }}">
    @include('users.partials.avatar', ['user' => $user] ) {{{ $user->username }}}
</a>