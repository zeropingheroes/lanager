<ul class="nav navbar-nav navbar-right">
	@if(Auth::check())
		<li class="dropdown">
			<a href="#" class="dropdown-toggle navbar-user" data-toggle="dropdown" title="{{{ Auth::user()->username }}}">
				<b class="caret"></b>
				@include('users.partials.avatar', ['user' => Auth::user()] )
			</a>
			<ul class="dropdown-menu">
				<li><a href="{{ route('users.show', Auth::user()->id) }}">{{ Icon::user() }} Profile</a></li>
				<li><a href="{{ route('sessions.logout') }}">{{ Icon::logOut() }} Log Out</a></li>
			</ul>
		</li>
	@else
		<li>
			<a href="{{ URL::route('sessions.login') }}" class="navbar-signin"><img src="{{ asset('/img/sits_small.png') }}" width="154" height="23"></a>
		</li>
	@endif
</ul>
