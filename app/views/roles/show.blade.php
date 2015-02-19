@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')
	<ul>
		@forelse($role->users as $user)
			<li>{{ $user->username }}</li>
		@empty
			<li>No users!</li>
		@endforelse
	</ul>
	{{ HTML::button('roles.create') }}
	{{ HTML::button('roles.edit', $role->id) }}
	{{ HTML::button('roles.destroy', $role->id) }}
@endsection				
