@if(! $user->SteamVisibility->apps_visible)
    <span class="badge badge-danger">Private</span>
@else
    <span class="badge badge-success">Public</span>
@endif