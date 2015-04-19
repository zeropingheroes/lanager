@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')

	<div>
		<strong>Logged:</strong> {{{ $log->created_at }}} ({{{ $log->created_at->diffForHumans() }}})
	</div>
	<div>
		<strong>SAPI:</strong> {{{ strtoupper($log->php_sapi_name) }}}
	</div>
	<div>
		<strong>Level:</strong> @include('logs.partials.level', ['level' => $log->level])
	</div>
	
	<h2>Message</h2>
	<div>
		{{{ $log->message }}}
	</div>

	<h2>Context</h2>
	<?php ini_set('xdebug.var_display_max_data', -1); ?>
	<div>{{ var_dump(json_decode($log->context)) }} </div>

@endsection
