{{ Form::label('name', 'Name') }}
{{ Form::text('name',NULL,array('placeholder' => 'The name of the achievement', 'maxlength' => 255)) }}

{{ Form::label('description', 'Description') }}
{{ Form::text('description',NULL,array('placeholder' => 'A description of how to attain the achievement, or what the achievement is')) }}

{{ Form::hidden('visible', '1') }}

{{ Button::normal('Submit')->submit() }}
