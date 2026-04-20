@if($value)
    <i class="fa-solid fa-check"></i>
@else
    @if(isset($showCross))
        <i class="fa-solid fa-xmark"></i>
    @endif
@endif
