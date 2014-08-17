@if (!count($shouts))
	<p>No shouts to show!</p>
@else
	@foreach ($shouts as $shout)
		<div class="media">
			<a class="pull-left" href="{{ URL::route('users.show', $shout->user->id) }}">
				{{ HTML::userAvatar($shout->user, 'small', 'media-object') }}
			</a>
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
					{{ Button::xs_submit('', array('title' => $pinVerb.' this shout', 'name' => 'Submit' ))->with_icon('star') }}
					{{ Form::close() }}
					{{ HTML::resourceDelete('shouts', $shout->id, 'Delete') }}
				</div>
			@endif
			<span class="pull-right shout-timestamp" title="{{ $shout->created_at }}">
				{{ $shout->created_at->diffForHumans() }}
			</span>
			@if ($shout->pinned)
				<span class="glyphicon glyphicon-star pull-right" title="This post has been pinned"></span>
			@endif
			<div class="media-body shout-body">
				<h4 class="media-heading">
					{{ link_to_route('users.show', $shout->user->username, $shout->user->id) }}
					@foreach($shout->user->roles as $role)
						<span class="badge">{{ $role->name }}</span>
					@endforeach
				</h4>
				{{{ $shout->content }}}
			</div>
		</div>
	@endforeach
@endif
