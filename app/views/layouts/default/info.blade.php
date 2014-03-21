<li class="dropdown {{ Request::is('infoPage*') ? 'active' : '' }}">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown">Info
		<b class="caret"></b>
	</a> 
	<ul class="dropdown-menu">
		@include('lanager-core::infopage.list')
		@if( Authority::can( 'manage', 'infoPage' ) )
			<li>{{ link_to_route('infoPage.create', 'Create...') }}</li>
		@endif
	</ul>
</li>