@php
    $hours = (new \Carbon\Carbon($lan->end))->diffInHours(new \Carbon\Carbon($lan->start));
@endphp
@lang('title.x-hours', ['x' => $hours])