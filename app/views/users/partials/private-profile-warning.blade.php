@if ($user->steam_visibility == 1 AND Auth::check() AND $user->id == Auth::user()->id)
    {{ Alert::danger('<strong>Oh no!</strong> Your Steam Profile is set to private or friends only. This means the LANager can\'t add you to the "popular games" or "popular servers" pages - please consider ' .
    link_to(SteamBrowserProtocol::openSteamPage('SteamIDEditPage'), 'changing your profile\'s privacy settings to public') . ', even if it\'s just for the event. Thanks!' ) }}
@endif