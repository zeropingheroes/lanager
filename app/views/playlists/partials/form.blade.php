{{ ControlGroup::generate(
	Form::label('name', 'Name'),
	Form::text('name',NULL, ['placeholder' => 'The name of the playlist', 'maxlength' => 255] ),
	NULL,
	2,
	9
)->withAttributes( ['class' => 'required'] )
}}

{{ ControlGroup::generate(
	Form::label('description', 'Description'),
	Form::text('description',NULL, ['placeholder' => 'A brief description of the playlist', 'maxlength' => 255] ),
	NULL,
	2,
	9
) }}

{{ ControlGroup::generate(
	Form::label('max_item_duration', 'Max Item Duration'),
	InputGroup::withContents(Form::text('max_item_duration', 600))->append('seconds'),
	NULL,
	2,
	9
) }}

{{ ControlGroup::generate(
	Form::label('user_skip_threshold', 'User Skip Threshold'),
	InputGroup::withContents(Form::text('user_skip_threshold', 50))->append('%'),
	Form::help('Active users required to vote to skip a playlist item'),
	2,
	9
) }}

<div class="row">
	<div class="col-md-2 col-md-offset-2">
		{{ Button::normal('Submit')->submit() }}
	</div>
</div>



