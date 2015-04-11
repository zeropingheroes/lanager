{{ ControlGroup::generate(
	Form::label('name', 'Name'),
	Form::text('name',NULL,['placeholder' => 'The name of the LAN', 'maxlength' => 255]),
	NULL,
	2,
	9
)->withAttributes( ['class' => 'required'] )
}}

{{ ControlGroup::generate(
	Form::label('start', 'Start'),
	View::make('datetimepicker', ['name' => 'start'] ),
	NULL,
	2,
	9
)->withAttributes( ['class' => 'required'] ) }}

{{ ControlGroup::generate(
	Form::label('end', 'End'),
	View::make('datetimepicker', ['name' => 'end'] ),
	NULL,
	2,
	9
)->withAttributes( ['class' => 'required'] ) }}

<div class="row">
	<div class="col-md-2 col-md-offset-2">
		{{ Button::normal('Submit')->submit() }}
	</div>
</div>
