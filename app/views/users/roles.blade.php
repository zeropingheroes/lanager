@extends('lanager-core::layouts.default')
@section('content')

<h2>{{ $user->username }} - Roles</h2>

{{ Form::open(array('route' => array('user.roles.update', $user->id), 'method' => 'PUT')) }}

@foreach($roles as $role)
	{{ Form::labelled_checkbox('userRoles[]', $role->name, $role->id, $user->hasRole($role->name)) }}
@endforeach

{{ Button::inverse_submit('Submit') }}

{{ Form::close() }}

@endsection