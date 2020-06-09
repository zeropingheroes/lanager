<?php

namespace Zeropingheroes\Lanager\Requests;

use Carbon\Carbon;
use Zeropingheroes\Lanager\Lan;

class StoreEventRequest extends Request
{
    use LaravelValidation;

    /**
     * Whether the request is valid.
     *
     * @return bool
     */
    public function valid(): bool
    {
        $this->validationRules = [
            'name' => ['required', 'max:255'],
            'start' => ['required', 'date_format:Y-m-d H:i:s', 'before:end'],
            'end' => ['required', 'date_format:Y-m-d H:i:s', 'after:start'],
            'signups_open' => [
                'nullable',
                'date_format:Y-m-d H:i:s',
                'before:signups_close',
                'before_or_equal:start',
                'required_with:signups_close',
            ],
            'signups_close' => [
                'nullable',
                'date_format:Y-m-d H:i:s',
                'after:signups_open',
                'before_or_equal:start',
                'required_with:signups_open',
            ],
            'lan_id' => ['required', 'numeric', 'exists:lans,id'],
            'published' => ['boolean'],
        ];

        if (! $this->laravelValidationPasses()) {
            return $this->setValid(false);
        }

        $lan = Lan::findOrFail($this->input['lan_id']);

        $eventStart = Carbon::make($this->input['start']);
        $eventEnd = Carbon::make($this->input['end']);

        if (! $eventStart->between($lan->start, $lan->end) ||
            ! $eventEnd->between($lan->start, $lan->end)
        ) {
            $this->addError(trans('phrase.event-times-must-be-within-lan-times'));

            return $this->setValid(false);
        }

        return $this->setValid(true);
    }
}
