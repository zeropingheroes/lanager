{{ ControlGroup::generate(
	Form::label('name', 'Name'),
	Form::text('name',NULL, ['placeholder' => 'The name of the event type', 'maxlength' => 255] ),
	NULL,
	2,
	9
)->withAttributes( ['class' => 'required'] )
}}

{{ ControlGroup::generate(
	Form::label('colour', 'Colour'),
	Form::text('colour',NULL,['placeholder' => 'The display colour to use for events of this type', 'maxlength' => 255]),
	Form::help('The colour encoded as hex, e.g. #FF12AC'),
	2,
	9
)
}}

<div class="row">
	<div class="col-md-2 col-md-offset-2">
		{{ Button::normal('Submit')->submit() }}
	</div>
</div>
