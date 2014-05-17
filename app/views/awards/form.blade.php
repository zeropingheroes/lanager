{{ HTML::validationErrors() }}

{{ Form::label('user_id', 'Attendee') }}
{{ Form::select('user_id', $users, Input::get('user_id')) }}

<br>

{{ Form::label('achievement_id', 'Achievement') }}
{{ Form::select('achievement_id', $achievements, Input::get('achievement_id')) }}

<br>

{{ Form::label('lan_id', 'LAN Achieved At') }}
{{ Form::select('lan_id', $lans) }}

<br>

{{ Button::submit('Submit') }}
