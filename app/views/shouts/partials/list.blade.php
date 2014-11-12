@forelse($shouts as $shout)
	<div class="media">
		<a class="pull-left" href="{{ URL::route('users.show', $shout->user->id) }}">
			@include('users.partials.avatar', ['user' => $shout->user, 'size' => 'medium', 'classes' => 'media-object'] )
		</a>
		@include('shouts.partials.management')
		<span class="pull-right shout-timestamp" title="{{ $shout->created_at }}">
			{{ $shout->created_at->diffForHumans() }}
		</span>
		@if ($shout->pinned)
			<span class="glyphicon glyphicon-pushpin pull-right" title="This post has been pinned"></span>
		@endif
		<div class="media-body shout-body">
			<h4 class="media-heading">
				{{ link_to_route('users.show', $shout->user->username, $shout->user->id) }}
			</h4>
			{{{ $shout->content }}}
		</div>
	</div>
@empty
	<p>No shouts to show!</p>
@endforelse