@php
    $steamAccount = $user->accounts->where('provider','steam')->first();
@endphp
@if( $steamAccount && $steamAccount->provider_id)
    <img src="{{ Vite::asset('resources/images/steam.svg') }}" width="20" height="20" alt="Steam Logo">
    <span class="ms-3">{{ $steamAccount->username }}</span>
    <a class="float-end me-3" href="steam://url/SteamIDPage/{{ $steamAccount->provider_id }}">
        <i class="fa-solid fa-arrow-up-right-from-square"></i>
    </a>
@endif
