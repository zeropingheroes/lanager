@if( ! count($roles) )
	<p>No roles assigned!</p>
@else
	<ul>
		@foreach($roles as $role)
			<li>{{{ $role->name }}}</li>
		@endforeach
	</ul>
@endif
