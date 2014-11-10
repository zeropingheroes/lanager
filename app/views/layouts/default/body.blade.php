<body style>
	@include('layouts.global.js')
	@include('layouts.default.nav')
	<div class="container content">
		<h1>{{ $title }}@if (isset($titleFloat)) {{ $titleFloat }} @endif</h1>
		{{ Notification::group('info', 'success', 'danger', 'warning')->showAll() }}
		@yield('content')
	</div>
	@include('layouts.default.footer')
</body>
