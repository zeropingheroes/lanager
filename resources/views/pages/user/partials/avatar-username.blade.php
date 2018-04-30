@if($user->id && $user->username)
    <a href="{{ route('users.show', $user->id) }}">
        @include('pages.user.partials.avatar', ['size' => 'small'])
        {{ $user->username }}
    </a>
@endif