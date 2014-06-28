<?php
$timestamps = array(
	time()-300,
	time()-600,
	time()-1800,
	time()-3600,
	time()-7200,
	);
$date = new ExpressiveDate();
?>
<p>View usage: 
@foreach($timestamps as $timestamp)
	{{ link_to(Request::url().'?timestamp='.$timestamp, $date->setTimestamp($timestamp)->getRelativeDate()) }}
@endforeach
</p>