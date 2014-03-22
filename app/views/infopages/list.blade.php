@if(!empty($infoPages))
	@foreach($infoPages as $infoPage)
		<li>{{ link_to_route('infopages.show',$infoPage->title, $infoPage->id) }}</li>
	@endforeach
@endif