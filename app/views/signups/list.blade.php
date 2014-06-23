@if(count($signups))
	{{ Table::open(array('class' => 'signups')) }}
	<?php
	foreach( $signups as $signup )
	{
		$tableBody[] = array(
			'user'		=> $signup->user->username,
			'event'		=> $signup->event->name,
			'controls'	=> HTML::resourceDelete('signups',$signup->id, '', 'trash'),
		);
	}
	?>
	{{ Table::body($tableBody) }}
	{{ Table::close() }}
@else
	No event signups!
@endif
