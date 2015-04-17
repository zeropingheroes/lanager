@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')

	@if(count($logs))
		<table class="table logs-index">
			<thead>
				<tr>
					<th>ID</th>
					<th class="time">Time</th>
					<th class="sapi">SAPI</th>
					<th>Level</th>
					<th>Message</th>
				</tr>
			</thead>
			<tbody>
			@foreach( $logs as $log )
				<tr>
					<td>
						{{{ $log->id }}}
					</td>
					<td title="{{{ $log->created_at }}}">
						{{{ $log->created_at->diffForHumans() }}}
					</td>
					<td>
						{{{ strtoupper($log->php_sapi_name) }}}
					</td>
					<td>
						@include('logs.partials.level', ['level' => $log->level])
					</td>
					<td>
						{{{ $log->message }}}
					</td>
				</tr>
			@endforeach
			</tbody>
		</table>
	@else
		<p>No log entries found!</p>
	@endif

@endsection
