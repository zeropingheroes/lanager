@php
    $selected = $selected ?? old($name, $item->{$name});
    $valueField = $valueField ?? 'id';
    $options = $options ?? $items->pluck($labelField, $valueField)->prepend('','');
@endphp
<select class="custom-select" id="{{ $name }}" name="{{ $name }}">
    @foreach($options as $value => $label)
        <option @if($value == $selected) selected @endif value="{{ $value }}">{{ $label }}</option>
    @endforeach
</select>

