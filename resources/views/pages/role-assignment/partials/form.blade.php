<div class="form-group">
    {{ Form::label('user_id', 'User') }}
    {{ Form::select('user_id', ['a','b','c']) }}
</div>

<div class="form-group">
    {{ Form::label('role_id', 'User') }}
    {{ Form::select('role_id', ['a','b','c']) }}
</div>

<div class="row">
    <div class="col-md-2 col-md-offset-2">
        {{ Form::submit('Submit') }}
    </div>
</div>
