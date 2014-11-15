<body style>
	@include('layouts.global.js')
	@include('layouts.default.nav')
	<div class="container content">
		@yield('content')
	</div>
	@include('layouts.default.footer')
</body>
