@if($user->id && $user->username)
    @php
    $steamAccount = $user->OAuthAccounts->where('provider','steam')->first();

    if($steamAccount) {

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
    @endphp
    <img class="avatar avatar-{{ $size }} avatar-{{ str_replace(' ', '-', strtolower($user->state->status())) }}"
         src="{{ $avatar }}"
         alt="Avatar for {{ $user->username }}">
@endif