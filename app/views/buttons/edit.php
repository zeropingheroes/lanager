<?php

// Set defaults for any options not specified
$options = [
	'url'  => $url,
	'text' => ( ( ! isset($text) ) ? '' : $text ),
	'icon' => ( ( ! isset($icon) ) ? 'pencil' : $icon ),
	'size' => ( ( ! isset($size) ) ? 'small' : $size ),
	'type' => ( ( ! isset($type) ) ? 'normal' : $type ),
	'hover' => ( ( ! isset($hover) ) ? 'Edit this item' : $hover ),
];

echo View::make('buttons.url', $options);