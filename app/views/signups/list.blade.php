@if(count($signups))
	{{ Table::open(array('class' => 'signups')) }}
	<?php
	foreach( $signups as $signup )
	{
		$tableBody[] = array(
			'user'		=> $signup->user->username,
			'event'		=> $signup->event->name,
			'controls'	=> HTML::button('signups.destroy',$signup->id, ['size' => 'xs']),
		);
	}
	?>
	{{ Table::body($tableBody) }}
	{{ Table::close() }}
@else
	No event signups!
@endif
