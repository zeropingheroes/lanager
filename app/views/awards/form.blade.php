{{ Form::label('user_id', 'Attendee') }}
{{ Form::select('user_id', $users, Input::get('user_id')) }}

{{ Form::label('achievement_id', 'Achievement') }}
{{ Form::select('achievement_id', $achievements, Input::get('achievement_id')) }}

{{ Form::label('lan_id', 'LAN Achieved At') }}
{{ Form::select('lan_id', $lans) }}

{{ Button::submit('Submit') }}
