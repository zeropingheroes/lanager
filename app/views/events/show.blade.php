@extends('layouts.default')
@section('content')
	<?php $eventTimespan = new Zeropingheroes\Lanager\Helpers\Timespan($event->start, $event->end); ?>
	@if( isset( $event->type->name ) )
		<h4>{{{ $event->type->name }}}</h4>
	@endif
	<div class="row">
		<div class="col-md-6">
			<h4>{{ $eventTimespan->naturalFormat() }}</h4>
		</div>
		<div class="col-md-6">
			<h4 class="pull-right">
				@if( $eventTimespan->status === 0 )
					{{ 'Starting ' . $eventTimespan->start->getRelativeDate() }}
				@elseif( $eventTimespan->status === 1 )
					{{ 'Started ' . $eventTimespan->start->getRelativeDate() . ', ending ' . $eventTimespan->end->getRelativeDate() }}
				@elseif( $eventTimespan->status === 2 )
					{{ 'Ended ' . $eventTimespan->end->getRelativeDate() }}
				@endif
			</h4>
		</div>
	</div>
	<hr>
	{{ Markdown::string($event->description) }}
	<br>
	{{ HTML::resourceUpdate('events', $event->id, 'Edit') }}

	{{ HTML::resourceDelete('events', $event->id, 'Delete') }}
	<br>
	<br>

	@if( isset($event->signup_opens) AND isset($event->signup_closes) )
		<?php
		$signupTimespan = new Zeropingheroes\Lanager\Helpers\Timespan($event->signup_opens, $event->signup_closes);
		switch($signupTimespan->status)
		{
			case 0:
				$signupStatusLabel = Label::info('Not Yet Open');
				$signupRelativeTime = 'Opening ' . $signupTimespan->start->getRelativeDate();
				break;
			case 1:
				$signupStatusLabel = Label::success('Open');
				$signupRelativeTime = 'Closing ' . $signupTimespan->end->getRelativeDate();
				break;
			case 2:
				$signupStatusLabel = Label::warning('Closed');
				$signupRelativeTime = 'Closed ' . $signupTimespan->end->getRelativeDate();
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

		@if(count($event->signups))
			<?php
			$tableBody = array();
			$controls = '';
			foreach( $event->signups as $signup )
			{

				if($signup->user_id == Auth::user()->id)
				{
					$controls = Form::open(
								array(
									'route' => array('signups.destroy',  $signup->id),
									'method' => 'DELETE',
									'class' => 'form-inline')
								).
					Button::xs_submit('', array('title' => 'Leave this event', 'name' => 'Submit' ))->with_icon('trash').
					Form::close();
				}

				$tableBody[] = array(
					'user'			=> '<a href="'.URL::route('users.show', $signup->user->id).'">'.HTML::userAvatar($signup->user).' '.e($signup->user->username).'</a>',
					'signup-time'	=> ExpressiveDate::make($signup->created_at)->getRelativeDate(),
					'controls'		=> $controls,
				);
				$controls = '';
			}
			?>
			{{ Table::open(array('class' => 'signups')) }}
			{{ Table::headers('User', 'Signed Up', '') }}
			{{ Table::body($tableBody) }}
			{{ Table::close() }}
		@else
			<p>No users signed up!</p>
		@endif
		@if(Authority::can('create', 'signup'))
			@if( ! $event->hasSignupFromUser(Auth::user()->id))
				{{ Form::open(
							array(
								'route' => 'signups.store',
								'method' => 'POST',
								'class' => 'form-inline')
							) }}
				{{ Form::hidden('event_id', $event->id) }}
				{{ Form::hidden('user_id', Auth::user()->id) }}
				{{ Button::submit('Sign Up', array('title' => 'Sign up to this event', 'name' => 'Submit' )) }}
				{{ Form::close() }}
			@endif
		@endif
	@endif
@endsection
