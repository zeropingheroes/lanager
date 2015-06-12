<?php
$buttonSize = 'extraSmall';
$links = [
	'editProfile' =>	[
								'hover' => 'Edit Profile',
								'icon' => 'pencil',
								'size' => $buttonSize,
								'url' => SteamBrowserProtocol::openSteamPage('SteamIDEditPage'),
							],
	'viewSteamProfile' =>	[
								'hover' =>  'View Steam Profile',
								'icon' =>  'newWindow',
								'size' => $buttonSize,
								'url' => 'http://www.steamcommunity.com/profiles/'.$user->steam_id_64,
							],
	'deleteAccount' =>		[
								'hover' =>'Delete Account',
								'icon' => 'trash',
								'size' => $buttonSize,
								'resource' => 'users',
								'item' => $user,
							],
	'addFriend' =>			[
								'hover' => 'Add Friend',
								'icon' => 'plus',
								'size' => $buttonSize,
								'url' => SteamBrowserProtocol::addFriend($user->steam_id_64),
							],
	'message' =>			[
								'hover' => 'Message',
								'icon' => 'envelope',
								'size' => $buttonSize,
								'url' => SteamBrowserProtocol::messageFriend($user->steam_id_64),
							],
];
?>
@if ( Auth::check() AND $user->id == Auth::user()->id )
	@include('buttons.url', $links['editProfile'])
@else
	@include('buttons.url', $links['addFriend'])
	@include('buttons.url', $links['message'])
@endif
	@include('buttons.url', $links['viewSteamProfile'])
	@include('buttons.destroy', $links['deleteAccount'])
