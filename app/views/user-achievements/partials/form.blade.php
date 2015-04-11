{{ ControlGroup::generate(
	Form::label('user_id', 'User'),
	Form::select('user_id', $users, Input::get('user_id')),
	NULL,
	2,
	9
)->withAttributes( ['class' => 'required'] )
}}

{{ ControlGroup::generate(
	Form::label('achievement_id', 'Achievement'),
	Form::select('achievement_id', $achievements, Input::get('achievement_id')),
	NULL,
	2,
	9
)->withAttributes( ['class' => 'required'] )
}}

{{ ControlGroup::generate(
	Form::label('lan_id', 'LAN'),
	Form::select('lan_id', $lans, Input::get('lan_id')),
	NULL,
	2,
	9
)->withAttributes( ['class' => 'required'] )
}}

<div class="row">
	<div class="col-md-2 col-md-offset-2">
		{{ Button::normal('Submit')->submit() }}
	</div>
</div>
