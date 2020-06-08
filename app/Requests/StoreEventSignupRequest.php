<?php

namespace Zeropingheroes\Lanager\Requests;

use Illuminate\Support\Facades\Auth;
use Zeropingheroes\Lanager\Event;
use Zeropingheroes\Lanager\User;

class StoreEventSignupRequest extends Request
{
    use LaravelValidation;

    /**
     * Whether the request is valid
     *
     * @return bool
     */
    public function valid(): bool
    {
        $this->validationRules = [
            'event_id' => ['required', 'numeric', 'exists:events,id'],
            'user_id' => ['required', 'numeric', 'exists:users,id'],
        ];

        if (!$this->laravelValidationPasses()) {
            return $this->setValid(false);
        }

        $event = Event::findOrFail($this->input['event_id']);
        $user = User::findOrFail($this->input['user_id']);

        if ($event->signups_open->isFuture() || $event->signups_close->isPast()) {
            $this->addError(trans('phrase.event-is-not-open-for-signups'));
            return $this->setValid(false);
        }

        if (Auth::user()->id != $user->id && !Auth::user()->hasRole('super-admin')) {
            $this->addError(trans('phrase.you-can-only-sign-yourself-up-to-event'));
            return $this->setValid(false);
        }

        return $this->setValid(true);
    }

}
