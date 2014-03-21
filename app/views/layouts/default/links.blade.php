<?php $links = Config::get('lanager-core::links'); ?>

@if (count($links) > 1 )
	<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown">Links
			<b class="caret"></b>
		</a> 
		<ul class="dropdown-menu">
@endif

	@foreach( $links as $linkName => $url )
		<li>
			<a href="{{ $url }}">{{{ $linkName }}}</a>
		</li>
	@endforeach

@if (count($links) > 1 )
		</ul>
	</li>
@endif