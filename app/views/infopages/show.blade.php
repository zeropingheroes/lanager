@extends('lanager-core::layouts.default')
@section('content')

<h2>{{{ $infoPage->title }}}</h2>

{{ Markdown::string($infoPage->content) }}

@if(!empty($infoPages))
	<br>
	<ul>
		@include('lanager-core::infoPage.list')
	</ul>
@endif

<br>

{{ HTML::resourceUpdate('infoPage',$infoPage->id, 'Edit') }}

{{ HTML::resourceDelete('infoPage',$infoPage->id, 'Delete') }}

@endsection