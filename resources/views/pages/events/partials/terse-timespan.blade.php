@php
    $start = (new \Carbon\Carbon($start));
    $end = (new \Carbon\Carbon($end));

    $formatTime = function (\Carbon\Carbon $time) {
        return $time->minute === 0 ? $time->format('ga') : $time->format('g:ia');
    };

    $getRelativeDay = function(\Carbon\Carbon $date) {
        $now = \Carbon\Carbon::now();
        if ($date->isSameDay($now)) {
            return 'Today';
        } elseif ($date->isSameDay($now->copy()->addDay())) {
            return 'Tomorrow';
        } else {
            return $date->format('D'); // Returns the abbreviated day name
        }
    };

    $formatDateRange = function (\Carbon\Carbon $start, \Carbon\Carbon $end) use ($formatTime, $getRelativeDay) {
        if ($start->isSameDay($end)) {
            return $getRelativeDay($start) . ' ' . $formatTime($start) . ' ' . __('phrase.timespan-to') . ' ' .$formatTime($end);
        } else {
            return $getRelativeDay($start) . ' ' . $formatTime($start) . ' ' . __('phrase.timespan-to') . ' ' .$getRelativeDay($end) . ' ' . $formatTime($end);
        }
    };

@endphp
<span title="{{ $start }} @lang('phrase.timespan-to') {{ $end }}">
    {{ $formatDateRange($start, $end) }}
</span>
