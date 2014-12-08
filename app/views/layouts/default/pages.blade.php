@if( isset($menuItems) )
<li class="dropdown {{ Request::is('pages*') ? 'active' : '' }}">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown">Info
		<b class="caret"></b>
	</a> 
	<ul class="dropdown-menu">
		@include('pages.list')
	</ul>
</li>
@endif