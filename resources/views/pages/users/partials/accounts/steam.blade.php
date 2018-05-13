@php
    $steamAccount = $user->OAuthAccounts->where('provider','steam')->first();
@endphp
@if( $steamAccount->provider_id)
    <img src="/img/steam.svg" width="20" height="20" alt="Steam Logo">
    <span class="ml-3">{{ $steamAccount->username }}</span>
    <a class="float-right mr-3" href="steam://url/SteamIDPage/{{ $steamAccount->provider_id }}">
        <span class="oi oi-external-link"></span>
    </a>
@endif