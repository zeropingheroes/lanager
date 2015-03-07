{{ Form::label('name', 'Name') }}
{{ Form::text('name', NULL, ['placeholder' => 'The name of the event', 'maxlength' => 255] ) }}

{{ Form::label('description', 'Description') }}
{{ Form::textarea('description',NULL,
	[
		'placeholder' => 'The event description, markdown formatting enabled. Tip: use relative links, e.g. [Install Guide](/pages/3) to easily link to other pages in the LANager.',
		'rows' => 10
	])
}}

{{ Form::help( link_to('https://daringfireball.net/projects/markdown/basics', 'Markdown formatting cheatsheet' ) ) }}

<div class="row">
	<div class="col-md-6">
		{{ Form::label('start', 'Start') }}
		{{ Form::dateTimePicker('start') }}
	</div>
	<div class="col-md-6">
		{{ Form::label('end', 'End') }}
		{{ Form::dateTimePicker('end') }}
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		{{ Form::label('signup_opens', 'Signup Opens') }}
		{{ Form::dateTimePicker('signup_opens') }}
	</div>
	<div class="col-md-6">
		{{ Form::label('signup_closes', 'Signup Closes') }}
		{{ Form::dateTimePicker('signup_closes') }}
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		{{ Form::label('event_type_id', 'Type') }}
		{{ Form::select('event_type_id', $eventTypes) }}
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		{{ Form::submit('Submit') }}
	</div>
</div>
