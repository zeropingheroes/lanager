@if (Auth::check())
	@if(Auth::user()->isAdmin())
		IP: {{{ $user->ip }}}
	@endif
@endif