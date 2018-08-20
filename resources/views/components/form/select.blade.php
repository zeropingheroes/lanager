@php
    // Build a value => label array from the passed in collection
    // Use 'id' as the value field unless otherwise specified
    $valueField = $valueField ?? 'id';
    $options = $options ?? $items->pluck($labelField, $valueField);

    $selected = $selected ?? old($name, $item->{$name}) ?? null;

    // Whether to include a blank option
    if (isset($blank) && $blank) {
        $options = $options->prepend('','');
    }

    $classes = $classes ?? 'custom-select';
@endphp

<select class="{{ $classes }}" id="{{ $name }}" name="{{ $name }}">
    @foreach($options as $value => $label)
        <option @if($value == $selected) selected @endif value="{{ $value }}">{{ $label }}</option>
    @endforeach
</select>

