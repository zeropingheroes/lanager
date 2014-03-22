@extends('layouts.default')
@section('content')

<h2>Info</h2>

<ul>
	@include('infoPage.list')
</ul>
<br>

{{ HTML::resourceCreate('infoPage', 'Create') }}

@endsection