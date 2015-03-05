@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')

	@forelse($pages as $page)
		<h2>{{ link_to_route('pages.show',$page->title, $page->id) }}</h2>
	@empty
		No info pages to show!
	@endforelse

	@include('buttons.create', ['resource' => 'pages'])

@endsection
