{{ ControlGroup::generate(
	Form::label('name', 'Name'),
	Form::text('name',null,['placeholder' => 'The name of the achievement', 'maxlength' => 255]),
	null,
	2,
	9
)->withAttributes( ['class' => 'required'] )
}}

{{ ControlGroup::generate(
	Form::label('description', 'Description'),
	Form::text('description',null,['placeholder' => 'A description of how to attain the achievement, or what the achievement is']),
	null,
	2,
	9
)->withAttributes( ['class' => 'required'] )
}}

<div class="row">
    <div class="col-md-2 col-md-offset-2">
        {{ Button::normal('Submit')->submit() }}
    </div>
</div>
