@if (Auth::check() AND $user->id == Auth::user()->id AND ($user->SteamApps->count() == 0 OR $user->state->visibility_status != 3))
    <div class="alert alert-danger" role="alert">
        <strong>@lang('phrase.oh-no')</strong>
        @lang('phrase.steam-profile-private')
        <br>
        <br>
        <a href="steam://url/SteamIDEditPage" class="alert-link">@lang('phrase.please-consider-public-visibility')</a>
    </div>
@endif