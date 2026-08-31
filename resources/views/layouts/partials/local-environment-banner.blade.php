@if(app()->isLocal())
    <div class="local-environment-banner text-center">
        <span class="badge text-bg-primary text-dark small">@lang('phrase.local-environment')</span>
    </div>
@endif
