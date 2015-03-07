@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')

	@if(count($pages))
		<table class="table">
			<thead>
				<tr>
					<th>Title</th>
					<th>Last Updated</th>
					@if( Authority::can('manage', 'pages') )
						<th class="text-center">{{ Icon::cog() }}</th>
					@endif
				</tr>
			</thead>
			<tbody>
			@foreach( $pages as $page )
				<tr>
					<td>{{ link_to_route('pages.show', $page->title, $page->id) }}</td>
					<td>{{ $page->updated_at->diffForHumans() }}</td>
					@if( Authority::can('manage', 'pages') )
						<td class="text-center">
							@include('buttons.edit', ['resource' => 'pages', 'item' => $page, 'size' => 'extraSmall'])
							@include('buttons.destroy', ['resource' => 'pages', 'item' => $page, 'size' => 'extraSmall'])
						</td>
					@endif
				</tr>
			@endforeach
			</tbody>
		</table>
	@else
		<p>No pages found!</p>
	@endif
	@include('buttons.create', ['resource' => 'pages'])
@endsection
