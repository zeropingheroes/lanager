<div class="shout-management">
	@if( Authority::can('manage', 'shouts'))
			{{ Form::inline(
							[
								'route' => [
									'shouts.destroy',
									'shout' => $shout->id,
								],
								'method' => 'DELETE',
								'data-confirm' => 'Are you sure you want to destroy this shout?',
								'class' => 'pull-right'
							]
						) }}
			{{ Button::normal( Icon::trash() )->extraSmall()->submit() }}
			{{ Form::close() }}

			{{ Form::inline(
							[
								'route' => [
									'shouts.update',
									'shout' => $shout->id,
								],
								'method' => 'PUT',
								'class' => 'pull-right'
							]
						) }}
			{{ Form::hidden('pinned', ($shout->pinned-1)*-1) }}
			{{ Button::normal( Icon::pushpin() )->extraSmall()->submit() }}
			{{ Form::close() }}
	@endif
</div>