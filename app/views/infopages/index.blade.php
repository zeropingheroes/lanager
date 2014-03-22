@extends('layouts.default')
@section('content')

<h2>Info</h2>

<ul>
	@include('infopages.list')
</ul>
<br>

{{ HTML::resourceCreate('infopages', 'Create') }}

@endsection