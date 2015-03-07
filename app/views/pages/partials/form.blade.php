{{ Form::label('title', 'Title') }}
{{ Form::text('title',NULL,['placeholder' => 'The title of the page', 'maxlength' => 255]) }}

{{ Form::label('content', 'Content') }}
{{ Form::textarea('content',NULL,
	[
		'placeholder' => 'The page content, markdown formatting enabled. Tip: use relative links, e.g. [Install Guide](/pages/3) to easily link to other pages in the LANager.',
		'rows' => 10
	])
}}
{{ Form::help('<a href="https://daringfireball.net/projects/markdown/basics" target="_blank">Markdown cheatsheet</a>') }}

{{ Form::label('parent_id', 'Parent') }}
{{ Form::select('parent_id', $pages) }}

{{ Button::normal('Submit')->submit() }}
