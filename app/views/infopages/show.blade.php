@extends('layouts.default')
@section('content')
	{{ Markdown::string($infoPage->content) }}
	@if(!empty($infoPages))
		<ul>
			@include('infopages.list')
		</ul>
	@endif
	{{ HTML::button('infopages.create') }}
	{{ HTML::button('infopages.edit', $infoPage->id) }}
	{{ HTML::button('infopages.destroy', $infoPage->id) }}
@endsection
