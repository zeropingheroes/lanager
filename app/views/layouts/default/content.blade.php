<div class="container content">
	<h1>{{ $title }}</h1>
	{{ Notification::group('info', 'success', 'danger', 'warning')->showAll() }}
	@yield('content')
</div>
