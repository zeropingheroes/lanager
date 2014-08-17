<?php
use Carbon\Carbon;

$timestamps = array(
	time()-300,
	time()-600,
	time()-1800,
	time()-3600,
	time()-7200,
	);
?>
<p>
{{ link_to(Request::url(), 'Now') }}
@foreach($timestamps as $timestamp)
	/ {{ link_to(Request::url().'?timestamp='.$timestamp, Carbon::createFromTimeStamp($timestamp)->diffForHumans()) }}
@endforeach
</p>