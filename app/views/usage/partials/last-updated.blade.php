@if( ! Input::has('timestamp') )
	<p>Last updated <span title="{{ $lastUpdated }}">{{ $lastUpdated->diffForHumans() }}</span></p>
@endif