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
        $status = str_replace(' ', '-', strtolower($user->state->status()));
    @endphp
    <img class="avatar avatar-{{ $size }} avatar-{{ $status }}"
         src="{{ $avatar }}"
         alt="@lang('phrase.avatar-for-username', ['username' => $user->username])"
         title="@lang('phrase.status-'.$status)">
@endif