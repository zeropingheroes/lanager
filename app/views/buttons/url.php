<?php

// Generic default options
$text = ( ( ! isset($text) ) ? '' : $text );
$icon = ( ( ! isset($icon) ) ? ''	  : $icon );
$size = ( ( ! isset($size) ) ? 'normal' : $size );
$type = ( ( ! isset($type) ) ? 'normal' : $type );
$hover = ( ( ! isset($hover) ) ? '' : $hover );
$target = ( ( ! isset($target) ) ? '' : $target );

// Create the basic button
$button = Button::{$type}($text);

// If an icon is specified add it
if( ! empty($icon) )	$button = $button->prependIcon(Icon::{$icon}());

// If hover text is specified add it
if( ! empty($hover) )	$button = $button->addAttributes(['title' => $hover]);
if( ! empty($target) )	$button = $button->addAttributes(['target' => $target]);

// If a size other than normal is specified set it
if( $size != 'normal' )	$button = $button->{$size}();

// Echo out finished button
echo $button->asLinkTo($url);