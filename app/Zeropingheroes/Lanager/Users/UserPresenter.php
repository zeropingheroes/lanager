<?php namespace Zeropingheroes\Lanager\Users;

use Laracasts\Presenter\Presenter;

class UserPresenter extends Presenter {

	/**
	 * Get the URL for the user's small avatar.
	 *
	 * @return string
	 */
	public function avatarSmall()
	{
		return $this->avatar;
	}
	/**
	 * Get the URL for the user's medium avatar.
	 *
	 * @return string
	 */
	public function avatarMedium()
	{
		return str_replace('.jpg', '_medium.jpg', $this->avatar);
	}

	/**
	 * Get the URL for the user's large avatar.
	 *
	 * @return string
	 */
	public function avatarLarge()
	{
		return str_replace('.jpg', '_full.jpg', $this->avatar);
	}


}