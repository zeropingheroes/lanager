@if($user->id && $user->username)
    @php
        $steamAccount = $user->accounts->where('provider','steam')->first();

        if($steamAccount) {

            $size = $size ?? 'small';

            switch($size) {
                case('small'):
                    $avatar = str_replace('_medium.jpg', '.jpg', $steamAccount->avatar);
                    break;

                case('medium'):
                    $avatar = $steamAccount->avatar;
                    break;

                case('large'):
                    $avatar = str_replace('_medium.jpg', '_full.jpg', $steamAccount->avatar);
                    break;

                default:
                    $avatar = str_replace('_medium.jpg', '.jpg', $steamAccount->avatar);
            }
        } else {
            $avatar = '';
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