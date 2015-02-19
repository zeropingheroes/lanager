{{ Form::label('name', 'Name') }}
{{ Form::text('name',NULL,array('placeholder' => 'The name of the event type', 'maxlength' => 255)) }}

{{ Form::label('colour', 'Colour') }}
{{ Form::text('colour',NULL,array('placeholder' => 'The display colour to use for events of this type', 'maxlength' => 255)) }}

{{ Button::normal('Submit')->submit() }}
