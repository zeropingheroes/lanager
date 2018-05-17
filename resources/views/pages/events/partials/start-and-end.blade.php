@php

$start = (new \Carbon\Carbon($event->start));
$end = (new \Carbon\Carbon($event->end));

// if timespan start falls on the hour, don't display minutes
if ( $start->minute == 0)
{
    $startFormat = 'D ga';
}
else
{
    $startFormat = 'D g:ia';
}
// if timespan end falls on the hour, don't display minutes
if ( $end->minute == 0)
{
    $endFormat = 'ga';
}
else
{
    $endFormat = 'g:ia';
}
// if timespan does not start and end on the same day, display the end day
if ( $start->day != $end->day )
{
    $endFormat = 'D '.$endFormat;
}
@endphp
<span title="{{ $start }} @lang('phrase.timespan-to') {{ $end }}">
    {{ $start->format($startFormat) }} @lang('phrase.timespan-to') {{  $end->format($endFormat) }}
</span>
