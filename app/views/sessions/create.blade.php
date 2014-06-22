@extends('layouts.default')
@section('content')
	<?php
		// Steam OpenID Login URL - cached for 1 day due to request time
		$steamAuthUrl = Cache::remember('steamAuthUrl', 60*24, function()
		{
			$openId = new LightOpenID(Request::server('HTTP_HOST'));
			
			$openId->identity = 'http://steamcommunity.com/openid';
			$openId->returnUrl = URL::route('sessions.create');
			return $openId->authUrl();
		});
	?>
	<div class="row">
		<div class="col-lg-8 col-centered steam-help">
			Sign in to the LANager with your Steam ID
		</div>
		<div class="col-lg-8 col-centered">
			<a href="{{ $steamAuthUrl }}" title="Click here to sign into the LANager using your Steam ID">
				<img src="{{ asset('/img/sits_large_noborder.png') }}" alt="Sign in through Steam Logo">
			</a>
		</div>
		<div class="col-lg-8 col-centered steam-help">
			Don't have a Steam account? You can <a href="https://store.steampowered.com/join/" target="_blank">create an account for free</a>.
		</div>
	</div>
@endsection
