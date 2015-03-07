<div class="shout-management">
	@include('buttons.destroy',
		[
			'resource' => 'shouts',
			'item' => $shout,
			'class' => 'inline pull-right',
			'size' => 'extraSmall',
		])
	@include('buttons.update',
		[
			'resource' => 'shouts',
			'item' => $shout,
			'class' => 'inline pull-right',
			'size' => 'extraSmall',
			'hover' => ( $shout->pinned == 0 ? 'Pin this shout' : 'Unpin this shout' ),
			'icon' => 'pushpin',
			'data' =>
				[
					'pinned' => (($shout->pinned-1)*-1),
				],
		])
</div>