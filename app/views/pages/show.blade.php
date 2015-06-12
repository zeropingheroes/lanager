@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')

	{{ Purifier::clean(Markdown::string($page->content), 'markdown') }}

	@if (!empty($page->children))
		<?php $children = $page->children()->orderBy(DB::raw('ISNULL(position)'))->get(); ?>
		<ul>
			@foreach($children as $child)
				<li>{{ link_to_route('pages.show',$child->title, $child->id) }}</li>
			@endforeach
		</ul>
	@endif
	
	@include('buttons.edit', ['resource' => 'pages', 'item' => $page] )
	@include('buttons.destroy', ['resource' => 'pages', 'item' => $page] )

@endsection				
