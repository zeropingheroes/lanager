@extends('layouts.default')
@section('content')
	@if(count($events))
		{{ Table::open(array('class' => 'events-list')) }}
		{{ Table::headers('Name', 'Time', '', 'Type', 'Signups', '') }}
		<?php
		foreach( $events as $event )
		{
			if(isset($event->signup_opens))
			{
				$signupTimespanTense = $event->present()->signupTimespanTense;
				switch($signupTimespanTense)
				{
					case 'Not yet open':	$signupTimespanTense = Label::info($signupTimespanTense);
						break;
					case 'Open':			$signupTimespanTense = Label::success($signupTimespanTense);
						break;
					case 'Closed':			$signupTimespanTense = Label::warning($signupTimespanTense);
						break;
				}
				$signupCount = count($event->users).' '.str_plural('user',count($event->users));
			}
			else
			{
				$signupTimespanTense = '';
				$signupCount = '';
			}

			$eventTense = $event->present()->timespanTense;
			switch($eventTense)
			{
				case 'Upcoming':	$eventTense = Label::info($eventTense);
					break;
				case 'In Progress':	$eventTense = Label::success($eventTense);
					break;
				case 'Ended':		$eventTense = Label::warning($eventTense);
					break;
			}

			$tableBody[] = array(
				'name'					=> link_to_route('events.show', $event->name, $event->id),
				'time'					=> $event->present()->timespan,
				'tense'					=> $eventTense,
				'type'					=> (isset($event->type->name) ? $event->type->name : 'asd'),
				'signup-count'			=> $signupCount,
				'signup-timespan-tense'	=> $signupTimespanTense,
			);
		}
		?>
		{{ Table::body($tableBody) }}
		{{ Table::close() }}
	@else
		No events found!
	@endif
@endsection
