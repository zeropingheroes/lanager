@extends('layouts.default')
@section('content')
	{{ Form::open(array('route' => array('users.roles.update', $user->id), 'method' => 'PUT')) }}
	@foreach($roles as $role)
		{{ Form::labelled_checkbox('userRoles[]', $role->name, $role->id, $user->hasRole($role->name)) }}
	@endforeach
	{{ Button::inverse_submit('Submit') }}
	{{ Form::close() }}
@endsection
