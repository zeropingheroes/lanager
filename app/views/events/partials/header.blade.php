<div class="row">
	<div class="col-md-8">
		<h1 class="pull-left">
			{{ $event->name }}
			@if ( isset( $event->type->name ) )
				<small>
					{{{ $event->type->name }}}
					@include('permalink', ['url' => URL::route('events.show', $event->id)] )
				</small>
			@endif
		</h1>
	</div>
	<div class="col-md-4">
		<h1 class="pull-right">
			{{ $event->present()->timespanStatusLabel }}
		</h1>
	</div>
</div>
