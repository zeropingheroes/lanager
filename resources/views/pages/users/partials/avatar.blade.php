@if($user->id && $user->username)
    @php
        $steamAccount = $user->OAuthAccounts->where('provider','steam')->first();

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

        if($user->state) {
            if($user->state->app->exists) {
                $status = 'in-game';
                $title = __('phrase.status-'.$status) . ': ' . $user->state->app->name;
            } else {
                $status = kebab_case($user->state->status->name);
                $title = __('phrase.status-'.$status);
            }
        } else {
            $status = 'unknown';
        }

    @endphp
    <img class="avatar avatar-{{ $size }} avatar-{{ $status }}"
         src="{{ $avatar }}"
         alt="@lang('phrase.avatar-for-username', ['username' => $user->username])"
         title="{{ $title }}">
@endif