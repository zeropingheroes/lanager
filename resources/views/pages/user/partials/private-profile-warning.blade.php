@if ($user->state->visibility_status == 3 AND Auth::check() AND $user->id == Auth::user()->id)
    <div class="alert alert-danger" role="alert">
        <strong>@lang('phrase.oh-no')</strong>
        @lang('phrase.steam-profile-private')
        <br>
        <br>
        <a href="steam://url/SteamIDEditPage" class="alert-link">@lang('phrase.please-consider-public-visibility')</a>
    </div>
@endif