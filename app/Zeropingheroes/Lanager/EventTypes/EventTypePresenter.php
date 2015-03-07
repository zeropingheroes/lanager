<?php namespace Zeropingheroes\Lanager\EventTypes;

use Laracasts\Presenter\Presenter;
use View;

class EventTypePresenter extends Presenter {

	public function colouredType()
	{
		$colour = ( is_null($this->colour) ) ? '' : $this->colour;

		return View::make('event-types.partials.label', ['colour' => $colour, 'name' => $this->name ]);
	}

}