@if(count($signups))
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
	{{ Table::withContents($tableBody) }}
	
@else
	No event signups!
@endif
