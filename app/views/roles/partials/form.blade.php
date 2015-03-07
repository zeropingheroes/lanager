{{ Form::label('name', 'Name') }}
{{ Form::text('name',NULL,['placeholder' => 'The name of the role', 'maxlength' => 255]) }}

{{ Button::normal('Submit')->submit() }}
