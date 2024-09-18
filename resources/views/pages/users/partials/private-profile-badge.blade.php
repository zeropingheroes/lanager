@if(! $user->steamMetadata->apps_visible)
    <span class="badge text-bg-danger">@lang('title.unavailable')</span>
@else
    <span class="badge text-bg-success">@lang('title.available')</span>
@endif
