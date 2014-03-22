@extends('layouts.default')
@section('content')

<h2>{{{ $infoPage->title }}}</h2>

{{ Markdown::string($infoPage->content) }}

@if(!empty($infoPages))
	<br>
	<ul>
		@include('infopages.list')
	</ul>
@endif

<br>

{{ HTML::resourceUpdate('infopages', $infoPage->id, 'Edit') }}

{{ HTML::resourceDelete('infopages', $infoPage->id, 'Delete') }}

@endsection