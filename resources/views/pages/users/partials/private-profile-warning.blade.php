<div class="alert alert-danger" role="alert">
    <strong>@lang('phrase.oh-no')</strong>
    @if (Auth::check() && $user->id == Auth::user()->id)
        @lang('phrase.your-steam-game-details-are-private')<br>
        <br>
        @lang('phrase.please-consider-public-visibility')<br>
        <br>
        <a href="steam://url/SteamIDEditPage" class="alert-link">@lang('phrase.edit-steam-profile')</a>
    @else
        @lang('phrase.usernames-game-details-are-private', ['username' => $user->username])
    @endif
</div>