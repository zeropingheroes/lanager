<div class="row mb-3">
    <div class="col-sm-2">@lang('title.name')</div>
    <div class="col-sm-10">{{ $event->name }}</div>
</div>

<div class="row mb-3">
    <div class="col-sm-2">@lang('title.start')</div>
    <div class="col-sm-10">{{ $event->start }}</div>
</div>

<div class="row mb-3">
    <div class="col-sm-2">@lang('title.end')</div>
    <div class="col-sm-10">{{ $event->end }}</div>
</div>

@if($event->signups_open)
    <div class="row mb-3">
        <div class="col-sm-2">@lang('title.signups-open')</div>
        <div class="col-sm-10">{{ $event->signups_open }}</div>
    </div>
@endif

@if($event->signups_close)
    <div class="row mb-3">
        <div class="col-sm-2">@lang('title.signups-close')</div>
        <div class="col-sm-10">{{ $event->signups_close }}</div>
    </div>
@endif

@if($event->description)
    <div class="row mb-3">
        <div class="col-sm-2">@lang('title.description')</div>
        <div class="col-sm-10">{!! GrahamCampbell\Markdown\Facades\Markdown::convert((string) $event->description) !!}</div>
    </div>
@endif
