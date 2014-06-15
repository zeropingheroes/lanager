@extends('layouts.default')
@section('content')
	{{ Markdown::string($infoPage->content) }}
	@if(!empty($infoPages))
		<ul>
			@include('infopages.list')
		</ul>
	@endif
	{{ HTML::resourceUpdate('infopages', $infoPage->id, 'Edit') }}
	{{ HTML::resourceDelete('infopages', $infoPage->id, 'Delete') }}
@endsection
