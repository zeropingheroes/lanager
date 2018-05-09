@if($value)
    <span class="oi oi-check" title="Cog" aria-hidden="true"></span>
@else
    @if(isset($showCross))
    <span class="oi oi-x" title="Cog" aria-hidden="true"></span>
    @endif
@endif