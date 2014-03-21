@extends('lanager-core::layouts.default')
@section('content')

<h2>Info</h2>

<ul>
	@include('lanager-core::infoPage.list')
</ul>
<br>

{{ HTML::resourceCreate('infoPage', 'Create') }}

@endsection