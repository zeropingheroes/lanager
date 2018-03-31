{{ ControlGroup::generate(
	Form::label('name', 'Name'),
	Form::text('name',null, ['placeholder' => 'The name of the event', 'maxlength' => 255] ),
	null,
	2,
	9
)->withAttributes( ['class' => 'required'] )
}}

{{ ControlGroup::generate(
	Form::label('description', 'Description'),
	Form::textarea('description',null,
		[
			'placeholder' => 'The event description, markdown formatting enabled. Tip: use relative links, e.g. [Install Guide](/pages/3) to easily link to other pages in the LANager.',
			'rows' => 10
		]),
	Form::help( link_to('https://daringfireball.net/projects/markdown/basics', 'Markdown formatting cheatsheet' ) ),
	2,
	9
) }}

{{ ControlGroup::generate(
	Form::label('start', 'Start'),
	View::make('datetimepicker', ['name' => 'start'] ),
	null,
	2,
	9
)->withAttributes( ['class' => 'required'] ) }}

{{ ControlGroup::generate(
	Form::label('end', 'End'),
	View::make('datetimepicker', ['name' => 'end'] ),
	null,
	2,
	9
)->withAttributes( ['class' => 'required'] ) }}

{{ ControlGroup::generate(
	Form::label('signup_opens', 'Signup Opens'),
	View::make('datetimepicker', ['name' => 'signup_opens'] ),
	Form::help('Leave signup fields blank to disable signups'),
	2,
	9
) }}

{{ ControlGroup::generate(
	Form::label('signup_closes', 'Signup Closes'),
	View::make('datetimepicker', ['name' => 'signup_closes'] ),
	null,
	2,
	9
) }}

{{ ControlGroup::generate(
	Form::label('event_type_id', 'Type'),
	Form::select('event_type_id', $eventTypes),
	null,
	2,
	9
)->withAttributes( ['class' => 'required'] ) }}

<div class="form-group">
    <label for="published" class="control-label col-sm-2">Published</label>
    <div class="checkbox col-sm-9">
        <label>
            {{ Form::hidden( 'published', '0' ) }}
            {{ Form::checkbox( 'published', true, true ) }} Published
        </label>
        {{ Form::help('Sets whether this event is published and therefore visible to everyone') }}
    </div>
</div>

<div class="row">
    <div class="col-md-2 col-md-offset-2">
        {{ Button::normal('Submit')->submit() }}
    </div>
</div>

