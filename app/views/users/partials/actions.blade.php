<?php
$pills = [
	'edit-steam-profile' =>	[
								'title' => Icon::pencil().' Edit Steam Profile',
								'link' => SteamBrowserProtocol::openSteamPage('SteamIDEditPage'),
							],
	'view-steam-profile' =>	[
								'title' =>  Icon::newWindow().' View Steam Profile',
								'link' => 'http://www.steamcommunity.com/profiles/'.$user->steam_id_64,
								'linkAttributes' => ['target' => '_blank'],
							],
	'delete-account' =>		[
								'title' => Icon::trash().' Delete Account',
								'link' => route('users.destroy', $user->id),
								'linkAttributes' => [
									'data-method' => 'DELETE',
									'data-confirm' => 'Are you sure?',
								],
							],
	'add-friend' =>			[
								'title' => Icon::plus().' Add Friend',
								'link' => SteamBrowserProtocol::addFriend($user->steam_id_64),
							],
	'message' =>			[
								'title' => Icon::envelope().' Message',
								'link' => SteamBrowserProtocol::messageFriend($user->steam_id_64),
							],
];

if( Auth::check() AND $user->id == Auth::user()->id ) // Logged in user viewing own profile
{
	echo Navigation::pills([
			$pills['edit-steam-profile'],
			$pills['view-steam-profile'],
			$pills['delete-account'],
		])->autoroute(false);
}
elseif ( Authority::can('manage', 'users', $user)) // Logged in user who can manage users
{
	echo Navigation::pills([
			$pills['add-friend'],
			$pills['view-steam-profile'],
			$pills['delete-account'],
		])->autoroute(false);
}
else // Default actions for guest/standard user
{
	echo Navigation::pills([
			$pills['add-friend'],
			$pills['message'],
			$pills['view-steam-profile'],
		])->autoroute(false);
}