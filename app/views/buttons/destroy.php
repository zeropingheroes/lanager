<?php

// Set defaults for any options not specified
$text  = ( ! isset($text)  ) ? '' : $text;
$icon  = ( ! isset($icon)  ) ? 'trash'  : $icon;
$size  = ( ! isset($size)  ) ? 'small' : $size;
$type  = ( ! isset($type)  ) ? 'normal' : $type;
$hover = ( ! isset($hover) ) ? 'Delete this item' : $hover;

$class = ( ! isset($class) ) ? 'inline' : $class;
$confirmation = ( ! isset($confirmation) ) ? 'Are you sure you want to delete this item?' : $confirmation;

// Echo the form
echo Form::inline(
					[
						'url' 			=> $url, 
						'method' 		=> 'DELETE',
						'data-confirm'	=> $confirmation,
						'class'			=> $class,
					]
				);

// Create the basic button
$button = Button::{$type}($text);

// If an icon is specified add it
if( ! empty($icon) )	$button = $button->prependIcon(Icon::{$icon}());

// If hover text is specified add it
if( ! empty($hover) )	$button = $button->addAttributes(['title' => $hover]);

// If a size other than normal is specified set it
if( $size != 'normal' )	$button = $button->{$size}();

// Echo out finished button
echo $button->submit();

// Close the form
echo Form::close();
