<?php

switch( $level )
{
	case 'debug':
		$class = 'text-muted';
		break;
	case 'info':
		$class = 'text-info';
		break;
	case 'notice':
		$class = 'text-info';
		break;
	case 'warning':
		$class = 'text-warning';
		break;
	case 'error':
		$class = 'text-danger';
		break;
	case 'critical':
		$class = 'text-danger';
		break;
	case 'alert':
		$class = 'text-danger';
		break;
	case 'emergency':
		$class = 'text-danger';
		break;
	default:
		$class = '';
}
?>
<span class="{{ $class }}">{{ $level }}</span>