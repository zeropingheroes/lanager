{{ ControlGroup::generate(
	Form::label('name', 'Name'),
	Form::text('name',NULL,['placeholder' => 'The name of the achievement', 'maxlength' => 255]),
	NULL,
	2,
	9
)->withAttributes( ['class' => 'required'] )
}}

{{ ControlGroup::generate(
	Form::label('description', 'Description'),
	Form::text('description',NULL,['placeholder' => 'A description of how to attain the achievement, or what the achievement is']),
	NULL,
	2,
	9
)
}}

<div class="row">
	<div class="col-md-2 col-md-offset-2">
		{{ Button::normal('Submit')->submit() }}
	</div>
</div>
