<?php namespace Zeropingheroes\Lanager\Domain\EventTypes;

use Laracasts\Presenter\Presenter;
use View;

class EventTypePresenter extends Presenter {

	/**
	 * Get the event's type label, coloured in its hex colour
	 * @return string
	 */
	public function colouredType()
	{
		$colour = ( is_null($this->colour) ) ? '' : $this->colour;

		return View::make('event-types.partials.label', ['colour' => $colour, 'name' => $this->name ]);
	}

}