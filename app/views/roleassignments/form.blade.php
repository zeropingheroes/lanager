{{ Form::label('user_id', 'User') }}
{{ Form::select('user_id', $users, Input::get('user_id')) }}

{{ Form::label('role_id', 'Role') }}
{{ Form::select('role_id', $roles, Input::get('role_id')) }}

{{ Button::submit('Submit') }}
