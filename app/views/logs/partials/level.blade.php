<?php

switch( $level )
{
	case 'debug':
		$class = 'label-default';
		break;
	case 'info':
		$class = 'label-info';
		break;
	case 'notice':
		$class = 'label-info';
		break;
	case 'warning':
		$class = 'label-warning';
		break;
	case 'error':
		$class = 'label-danger';
		break;
	case 'critical':
		$class = 'label-danger';
		break;
	case 'alert':
		$class = 'label-danger';
		break;
	case 'emergency':
		$class = 'label-danger';
		break;
	default:
		$class = '';
}
?>
<span class="label {{ $class }}">{{ ucwords($level) }}</span>