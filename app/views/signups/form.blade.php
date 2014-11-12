{{ Form::label('user_id', 'User') }}
{{ Form::select('user_id', $users, Input::get('user_id')) }}

{{ Form::label('event_id', 'Event') }}
{{ Form::select('event_id', $events, Input::get('event_id')) }}

{{ Button::normal('Submit')->submit() }}
