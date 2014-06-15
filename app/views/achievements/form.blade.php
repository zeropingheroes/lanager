{{ Form::label('name', 'Name') }}
{{ Form::text('name',NULL,array('placeholder' => 'The name of the achievement', 'maxlength' => 255)) }}

{{ Form::label('description', 'Description') }}
{{ Form::text('description',NULL,array('placeholder' => 'A description of how to attain the achievement, or what the achievement is')) }}

{{ Form::label('visible', 'Visibility') }}
{{ Form::labelled_checkbox('visible', 'Show the achievement even if it has not been achieved yet', '1', true) }}
{{ Form::block_help('Make achievements that you don\'t want to encourage invisible.') }}

{{ Button::submit('Submit') }}
