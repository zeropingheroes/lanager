@if($datetime)
    <span title="{{ $datetime->toDayDateTimeString() }}">{{ $datetime->diffForHumans() }}</span>
@endif