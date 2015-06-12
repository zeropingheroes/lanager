<?php

$form['url'] 	= $url;
$form['method'] = $method;
$form['class'] 	= ( ! isset($class) ) ? 'inline' : $class;
if ( isset( $id ) && ! empty( $id ) ) $form['id'] = $id;
if ( isset( $confirmation) && ! empty( $confirmation) )	$form['data-confirm'] = $confirmation;

// Echo the form
echo Form::inline( $form );

// Set any hidden input data
if ( isset($data) && is_array($data) )
{
	foreach($data as $name => $value)
	{
		echo Form::hidden( $name, $value );
	}
}

// Create the basic button
$button = Button::{$type}($text);

// If an icon is specified add it
if ( ! empty($icon) )	$button = $button->prependIcon(Icon::{$icon}());

// If hover text is specified add it
if ( ! empty($hover) )	$button = $button->addAttributes(['title' => $hover]);

// If a size other than normal is specified set it
if ( $size != 'normal' )	$button = $button->{$size}();

// Echo out finished button
echo $button->submit();

// Close the form
echo Form::close();