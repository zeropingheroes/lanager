@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')

	{{ Purifier::clean(Markdown::string($page->content), 'markdown') }}

	@if(!empty($page->children))
		<ul>
			@include('pages.partials.list', ['pages' => $page->children])
		</ul>
	@endif
	{{ HTML::button('pages.create') }}
	{{ HTML::button('pages.edit', $page->id) }}
	{{ HTML::button('pages.destroy', $page->id) }}
@endsection				
