{{ Form::label('name', 'Name') }}
{{ Form::text('name',NULL,['placeholder' => 'The name of the achievement', 'maxlength' => 255]) }}

{{ Form::label('description', 'Description') }}
{{ Form::text('description',NULL,['placeholder' => 'A description of how to attain the achievement, or what the achievement is']) }}

{{ Button::normal('Submit')->submit() }}
