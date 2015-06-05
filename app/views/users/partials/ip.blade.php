@if(Auth::user()->isAdmin())
	<small>IP: {{{ $user->ip }}}</small>
@endif