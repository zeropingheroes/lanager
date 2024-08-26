@if(! $user->steamMetadata->apps_visible)
    <span class="badge text-bg-danger">Private</span>
@else
    <span class="badge text-bg-success">Public</span>
@endif
