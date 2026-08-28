@php
    $required = $required ?? false;
    $class = $class ?? 'col-sm-2 col-form-label';
@endphp
<label
    @if(isset($for)) for="{{ $for }}" @endif
    class="{{ $class }}{{ $required ? ' required' : '' }}"
>
    {{ $text }}
</label>
