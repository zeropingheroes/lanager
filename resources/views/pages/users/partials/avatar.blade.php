@if($user->id && $user->username)
    @php

        $size = $size ?? 'small';

        $steamAccount = $user->accounts->where('provider','steam')->first();

        if($steamAccount) {
            $avatar = match ($size) {
                'medium' => $steamAccount->avatar,
                'large' => str_replace('_medium.jpg', '_full.jpg', $steamAccount->avatar),
                default => str_replace('_medium.jpg', '.jpg', $steamAccount->avatar),
            };
        } else {
            $avatar = Vite::asset('resources/images/default-avatar.png');
        }

$activeSession = $user->steamAppSessions()->active()->first();

if($activeSession) {
    $statusName = 'in-game';
    $statusDisplayName = __('phrase.status-in-game-x', ['x' => $activeSession->app->name]);
} elseif(
    $user->steamMetadata->exists &&
    $user->steamMetadata->profile_updated_at != null &&
    $user->steamMetadata->profile_updated_at->greaterThan(now()->subMinutes(3))
) {
    $statusName = $user->steamMetadata->status->name;
    $statusDisplayName = $user->steamMetadata->status->display_name;
} else {
    $statusName = 'unknown';
    $statusDisplayName = __('phrase.status-unknown');
}

    @endphp
    <img class="avatar avatar-{{ $size }} avatar-{{ $statusName }}"
     src="{{ $avatar }}"
     alt="@lang('phrase.avatar-for-username', ['username' => $user->username])"
     title="{{ $statusDisplayName }}">@endif
