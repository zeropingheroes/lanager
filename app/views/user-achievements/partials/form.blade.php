{{ Form::label('user_id', 'User') }}
{{ Form::select('user_id', $users, Input::get('user_id')) }}

{{ Form::label('achievement_id', 'Achievement') }}
{{ Form::select('achievement_id', $achievements, Input::get('achievement_id')) }}

{{ Form::label('lan_id', 'LAN') }}
{{ Form::select('lan_id', $lans) }}

{{ Button::normal('Submit')->submit() }}
