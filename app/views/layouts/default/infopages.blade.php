w<li class="dropdown {{ Request::is('infopages*') ? 'active' : '' }}">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown">Info
		<b class="caret"></b>
	</a> 
	<ul class="dropdown-menu">
		@include('infopages.list')
		@if( Authority::can( 'manage', 'infopages' ) )
			<li>{{ link_to_route('infopages.create', 'Create...') }}</li>
		@endif
	</ul>
</li>