@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')

	@if(count($lans))
		<table class="table">
			<thead>
				<tr>
					<th>Name</th>
					<th>Start</th>
					<th>End</th>
				</tr>
			</thead>
			<tbody>
			@foreach( $lans as $lan )
				<tr>
					<td>{{ link_to_route('lans.show', $lan->name, $lan->id) }}</td>
					<td>{{ $lan->start }}</td>
					<td>{{ $lan->end }}</td>
				</tr>
			@endforeach
			</tbody>
		</table>
	@else
		No LANs found!
	@endif

	{{ HTML::button('lans.create') }}
@endsection
