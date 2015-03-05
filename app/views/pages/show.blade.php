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
	
	@include('buttons.edit', ['resource' => 'pages', 'item' => $page] )
	@include('buttons.destroy', ['resource' => 'pages', 'item' => $page] )

@endsection				
