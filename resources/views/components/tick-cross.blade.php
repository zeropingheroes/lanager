@if($value)
    <span class="oi oi-check" title="Yes" aria-hidden="true"></span>
@else
    @if(isset($showCross))
    <span class="oi oi-x" title="No" aria-hidden="true"></span>
    @endif
@endif
