<?php
use Carbon\Carbon;

$timestamps = array(
	time()-(60*5),
	time()-(60*15),
	time()-(60*30),
	time()-(60*60),
	time()-(60*60*2),
	time()-(60*60*4),
	time()-(60*60*6),
	time()-(60*60*8),
	time()-(60*60*12),
	);

$dropdownItems[0]['url'] = Request::url();
$dropdownItems[0]['label'] = 'Now';

$i = 1;
foreach($timestamps as $timestamp)
{
	$dropdownItems[$i]['url'] = Request::url().'?timestamp='.($timestamp);
	$dropdownItems[$i]['label'] = Carbon::createFromTimeStamp($timestamp)->diffForHumans();
	$i++;
}

echo DropdownButton::normal( Icon::time() . ' View History')->withContents($dropdownItems);