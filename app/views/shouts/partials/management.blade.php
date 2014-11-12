@if( Authority::can('manage', 'shouts'))
	<div class="shout-moderation pull-right">
		{{ Form::open(
					array(
						'route' => array(
							'shouts.update',
								'shout' => $shout->id,
						),
						'method' => 'PUT',
						'class' => 'form-inline')
					) }}
		{{ Form::hidden('pinned', ($shout->pinned-1)*-1) }}
		<?php $pinVerb = $shout->pinned ? 'Unpin' : 'Pin'; ?>
		{{ Button::normal('Pin', array('title' => $pinVerb.' this shout', 'name' => 'Submit' ))->prependIcon(Icon::pushpin())->submit() }}
		{{ Form::close() }}
		{{ HTML::button('shouts.destroy', $shout->id) }}
	</div>
@endif