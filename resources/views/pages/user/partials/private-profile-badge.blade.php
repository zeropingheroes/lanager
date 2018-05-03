@if($user->SteamApps->count() == 0 OR $user->state->visibility_status != 3)
    <span class="badge badge-danger">Private</span>
@else
    <span class="badge badge-success">Public</span>
@endif