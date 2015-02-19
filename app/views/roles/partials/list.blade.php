@if(!empty($roles))
	@foreach($roles as $role)
		<li>{{ link_to_route('roles.show',$role->name, $role->id) }}</li>
	@endforeach
@endif
