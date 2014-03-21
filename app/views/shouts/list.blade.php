@if (!count($shouts))
	<p>No shouts to show!</p>
@else
	@foreach ($shouts as $shout)
		<?php $date = new ExpressiveDate($shout->created_at); ?>
		<div class="media">
			<a class="pull-left" href="{{ URL::route('user.show', $shout->user->id) }}">
				{{ HTML::userAvatar($shout->user, 'small', 'media-object') }}
			</a>
			@if( Authority::can('manage', 'shout'))
				<div class="shout-moderation pull-right">
					{{ Button::link(URL::route('shout.pin', array('shout' => $shout->id)), 'Pin') }}
					{{ HTML::resourceDelete('shout', $shout->id, 'Delete') }}
				</div>
			@endif
			<span class="pull-right shout-timestamp" title="{{ $date }}">
				{{ $date->getRelativeDate() }}
			</span>
			@if ($shout->pinned)
				<span class="glyphicon glyphicon-star pull-right" title="This post has been pinned"></span>
			@endif
			<div class="media-body shout-body">
				<h4 class="media-heading">
					{{ link_to_route('user.show', $shout->user->username, $shout->user->id) }}
					@foreach($shout->user->roles as $role)
						<span class="badge">{{ $role->name }}</span>
					@endforeach
				</h4>
				{{{ $shout->content }}}
			</div>
		</div>
	@endforeach
@endif