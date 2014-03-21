@extends('lanager-core::layouts.default')
@section('content')

<?php $eventTimespan = new Zeropingheroes\LanagerCore\Helpers\Timespan($event->start, $event->end); ?>

<div class="row">
	<div class="col-md-6">
		<h1>{{{ $event->name }}}</h1>
	</div>
	@if( isset( $event->event_type->name ) )
		<div class="col-md-6">
			<h1 class="pull-right">{{{ $event->event_type->name }}}</h1>
		</div>
	@endif
</div>
<div class="row">
	<div class="col-md-6">
		<h4>{{ $eventTimespan->naturalFormat() }}</h4>
	</div>
	<div class="col-md-6">
		<h4 class="pull-right">
			@if( $eventTimespan->status === 0 )
				{{ 'Starting '.$eventTimespan->start->getRelativeDate() }}
			@elseif( $eventTimespan->status === 1 )
				{{ 'Started '.$eventTimespan->start->getRelativeDate().', ending '.$eventTimespan->end->getRelativeDate() }}
			@elseif( $eventTimespan->status === 2 )
				{{ 'Ended '.$eventTimespan->end->getRelativeDate() }}
			@endif
		</h4>
	</div>
</div>
<hr>
{{ Markdown::string($event->description) }}
<br>
{{ HTML::resourceUpdate('event',$event->id, 'Edit') }}

{{ HTML::resourceDelete('event',$event->id, 'Delete') }}
<br>
<br>

@if( isset($event->signup_opens) AND isset($event->signup_closes) )
	<?php
	$signupTimespan = new Zeropingheroes\LanagerCore\Helpers\Timespan($event->signup_opens, $event->signup_closes);
	switch($signupTimespan->status)
	{
		case 0:
			$signupStatusLabel = Label::info('Not Yet Open');
			$signupRelativeTime = 'Opening '.$signupTimespan->start->getRelativeDate();
			break;
		case 1:
			$signupStatusLabel = Label::success('Open');
			$signupRelativeTime = 'Closing '.$signupTimespan->end->getRelativeDate();
			break;
		case 2:
			$signupStatusLabel = Label::warning('Closed');
			$signupRelativeTime = 'Closed '.$signupTimespan->end->getRelativeDate();
			break;
	}
	?>

	<div class="row">
		<div class="col-md-6">
			<h4>Signups {{ $signupStatusLabel }}</h4>
		</div>
		<div class="col-md-6">
			<h4 class="pull-right">{{ $signupRelativeTime }}</h4>
		</div>
	</div>
	<hr>

	@if(count($event->users))
		<?php
		$tableBody = array();
		foreach( $event->users as $user )
		{
			$tableBody[] = array(
				'user'			=> '<a href="'.URL::route('user.show', $user->id).'">'.HTML::userAvatar($user).' '.e($user->username).'</a>',
				'signup-time'	=> ExpressiveDate::make($user->pivot->created_at)->getRelativeDate(),
			);
		}
		?>
		{{ Table::open(array('class' => 'signups')) }}
		{{ Table::headers('User', 'Signed Up') }}
		{{ Table::body($tableBody) }}
		{{ Table::close() }}
	@else
		<p>No users signed up!</p>
	@endif
	@if( Auth::check() )
		@if( !$event->users->contains(Auth::user()) AND $signupTimespan->status === 1)
			{{ Button::link(URL::route('event.join', array('event' => $event->id)), 'Join') }}
		@elseif( $event->users->contains(Auth::user()) )
			{{ Button::link(URL::route('event.leave', array('event' => $event->id)), 'Leave') }}
		@endif
	@endif
@endif
<br>
<br>

@endsection