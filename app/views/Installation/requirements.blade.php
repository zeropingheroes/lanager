@extends('layout')
@section('content')

<h2>Installation</h2>

<p>Welcome! To make sure that the LANager application runs as smoothly as possible, this page has automatically checked your system meets the requirements below.
Once all requirements have been satisfied, the LANager can run through the installation process.</p>

<table id="requirements">
	<thead>
		<tr>
			<th colspan="2">Requirement</th>
			<th>Action Required</th>
		</tr>
	</thead>
	<tbody>
	@foreach($requirements as $requirement => $detail)
		@if($detail['result'])
			<tr class="requirementPass">
				<td>&#10004;</td>
				<td>{{ $detail['test'] }}</td>
				<td>None</td>
			</tr>
		@else
			<tr class="requirementFail">
				<td>&#10008;</td>
				<td>{{ $detail['test'] }}</td>
				<td>{{ $detail['action'] }}</td>
			</tr>
		@endif
	@endforeach
	</tbody>
</table>
<br>
<br>

@if($failureCount == 0)
	<span id="requirementResults" class="requirementPass">All requirements have been satisfied! {{ link_to('install/run', 'Click here to install the LANager') }}</span>
@else
	<span id="requirementResults" class="requirementFail">{{ $failureCount }} requirement(s) have not been satisfied. Once resolved, the installation can continue.</span>
@endif

@endsection