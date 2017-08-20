{{ ControlGroup::generate(
	Form::label('user_id', 'User'),
	Form::select('user_id', $users, Input::get('user_id')),
	null,
	2,
	9
)->withAttributes( ['class' => 'required'] )
}}

{{ ControlGroup::generate(
	Form::label('role_id', 'Role'),
	Form::select('role_id', $roles, Input::get('role_id')),
	null,
	2,
	9
)->withAttributes( ['class' => 'required'] )
}}

<div class="row">
    <div class="col-md-2 col-md-offset-2">
        {{ Button::normal('Submit')->submit() }}
    </div>
</div>
