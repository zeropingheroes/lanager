@if($datetime)
    <span title="{{ $datetime->diffForHumans() }}">{{ $datetime }}</span>
@endif
