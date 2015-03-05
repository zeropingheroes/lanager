<?php

// Set defaults for any options not specified
$options = [
	'url'  => $url,
	'text' => ( ( ! isset($text) ) ? '' : $text ),
	'icon' => ( ( ! isset($icon) ) ? 'file'	  : $icon ),
	'size' => ( ( ! isset($size) ) ? 'small' : $size ),
	'type' => ( ( ! isset($type) ) ? 'normal' : $type ),
	'hover' => ( ( ! isset($hover) ) ? 'Create a new item' : $hover ),
];

echo View::make('buttons.url', $options);