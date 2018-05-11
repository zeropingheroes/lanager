@php
    // Build a value => label array from the passed in collection
    // Use 'id' as the value field unless otherwise specified
    $valueField = $valueField ?? 'id';
    $options = $options ?? $items->pluck($labelField, $valueField);

    // Set which item is selected in preference order:
    // none / the passed in item / the old input
    $defaultSelected = $item->{$name} ?? null;
    $selected = $selected ?? old($name, $defaultSelected);

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

