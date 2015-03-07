<?php $class = ( isset($class) ) ? $class : 'pull-left'; ?>

<a class="{{ $class }}" href="{{ URL::route('users.show', $user->id) }}">
	@include('users.partials.avatar', ['user' => $user] ) {{ $user->username }}
</a>