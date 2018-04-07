<a href="{{ route('users.show', $user->id) }}">
    @include('pages.user.partials.avatar', ['size' => 'small', 'status' => 'offline'])
    {{ $user->username }}
</a>
