@if( Auth::user()->hasRole('Super Admin') )
	<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" title="">
			{{ Icon::cog() }} Admin
		</a>
		<ul class="dropdown-menu">
			<li><a href="{{ route('role-assignments.index') }}">{{ Icon::user() }} Assign Roles</a></li>
		</ul>
	</li>
@endif