{{ Form::label('name', 'Name') }}
{{ Form::text('name',NULL,['placeholder' => 'The name of the LAN', 'maxlength' => 255]) }}

<div class="row">
	<div class="col-md-6">
		{{ Form::label('start', 'Start') }}
		{{ Form::dateTimePicker('start') }}
	</div>
	<div class="col-md-6">
		{{ Form::label('end', 'End') }}
		{{ Form::dateTimePicker('end') }}
	</div>
</div>

{{ Button::normal('Submit')->submit() }}
