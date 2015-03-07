@forelse($shouts as $shout)
	@if ($shout->pinned)
		<div class="media bg-info shout-pinned" title="This shout has been pinned">
	@else
		<div class="media">
	@endif
		<a class="pull-left" href="{{ URL::route('users.show', $shout->user->id) }}">
			@include('users.partials.avatar', ['user' => $shout->user, 'size' => 'medium', 'classes' => 'media-object'] )
		</a>
		@include('shouts.partials.management')
		<div class="media-body shout-body">
			<h4 class="media-heading">
				{{ link_to_route('users.show', $shout->user->username, $shout->user->id) }}
				@include('roles.partials.badges', ['roles' => $shout->user->roles])
			</h4>
			
			{{ Purifier::clean(Markdown::string($shout->content), 'shout') }}
			
			<div>
				<small title="{{ $shout->created_at }}" class="timestamp">
					{{ $shout->created_at->diffForHumans() }}
				</small>
			</div>
		</div>
	</div>
@empty
	<p>No shouts to show!</p>
@endforelse