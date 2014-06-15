<div class="container content">
	<h1>{{ $title }}</h1>
	<?php
	$errors = Session::get('errors');
	if( $errors )
	{
		echo Alert::error(
			'<strong>The following errors occurred</strong>'.
			HTML::ul($errors->all(':message'))
		);
	}
	?>
	@yield('content')
</div>
