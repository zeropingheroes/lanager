@if (Auth::check() && $user->id == Auth::user()->id && (! $user->SteamVisibility->apps_visible))
    <div class="alert alert-danger" role="alert">
        <strong>@lang('phrase.oh-no')</strong>
        @lang('phrase.steam-profile-private')
        <br>
        <br>
        @lang('phrase.please-consider-public-visibility')
        <br>
        <br>
        <a href="steam://url/SteamIDEditPage" class="alert-link">@lang('phrase.edit-steam-profile')</a>
    </div>
@endif