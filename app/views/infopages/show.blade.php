@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')
	{{ Markdown::string($infoPage->content) }}
	@if(!empty($infoPages))
		<ul>
			@include('infopages.partials.list')
		</ul>
	@endif
	{{ HTML::button('infopages.create') }}
	{{ HTML::button('infopages.edit', $infoPage->id) }}
	{{ HTML::button('infopages.destroy', $infoPage->id) }}
@endsection
