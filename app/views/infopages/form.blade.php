{{ HTML::validationErrors() }}

{{ Form::label('title', 'Title') }}
{{ Form::text('title',NULL,array('placeholder' => 'The title of the page', 'maxlength' => 255)) }}

{{ Form::label('content', 'Content') }}
{{ Form::textarea('content',NULL,array('placeholder' => 'The content of the page, markdown formatting enabled', 'rows' => 10)) }}

{{ Form::block_help('<a href="https://daringfireball.net/projects/markdown/basics" target="_blank">Markdown cheatsheet</a>') }}

{{ Form::label('parent_id', 'Parent') }}
{{ Form::select('parent_id', $infoPagesDropdown) }}

{{ Button::submit('Submit') }}