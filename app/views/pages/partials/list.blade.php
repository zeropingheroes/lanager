@if(!empty($pages))
	@foreach($pages as $page)
		<li>{{ link_to_route('pages.show',$page->title, $page->id) }}</li>
	@endforeach
@endif
