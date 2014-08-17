@extends('layouts.default')
@section('content')
	@if( isset( $event->type->name ) )
		<h4>{{{ $event->type->name }}}</h4>
	@endif
	<div class="row">
		<div class="col-md-6">
			<h4>{{ $event->present()->timespan }}</h4>
		</div>
		<div class="col-md-6">
			<h4 class="pull-right">
				{{ $event->present()->timespanRelativeToNow }} {{ $event->present()->timespanStatusLabel }}
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
		<div class="row">
			<div class="col-md-6">
				<h4>Signups {{ $event->present()->signupTimespanStatusLabel }}</h4>
			</div>
			<div class="col-md-6">
				<h4 class="pull-right">{{ $event->present()->signupTimespanRelativeToNow }}</h4>
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
					'signup-time'	=> $signup->created_at->diffForHumans(),
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
