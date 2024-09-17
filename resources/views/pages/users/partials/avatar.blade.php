@if($user->id && $user->username)
    @php
        $steamAccount = $user->accounts->where('provider','steam')->first();

        $size = $size ?? 'small';

        if($steamAccount) {
            $avatar = match ($size) {
                'medium' => $steamAccount->avatar,
                'large' => str_replace('_medium.jpg', '_full.jpg', $steamAccount->avatar),
                default => str_replace('_medium.jpg', '.jpg', $steamAccount->avatar),
            };
        } else {
            $avatar = $user->accounts->first()->avatar;
        }

        $activeSession = $user->steamAppSessions->first();

        if($activeSession) {
            $statusName = 'in-game';
            $statusDisplayName = __('phrase.status-in-game-x', ['x' => $activeSession->app->name]);
        } elseif($user->steamMetadata->exists) {
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
         title="{{ $statusDisplayName }}">
@endif
