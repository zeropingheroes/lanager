<?php

declare(strict_types=1);

namespace Zeropingheroes\Lanager\Requests;

use Illuminate\Validation\Rule;
use Zeropingheroes\Lanager\Models\Lan;

class StoreLanRequest extends Request
{
    use LaravelValidation;

    /**
     * {@inheritDoc}
     */
    #[\Override]
    public function valid(): bool
    {
        $this->validationRules = [
            'name' => [
                'required',
                'max:255',
                Rule::unique('lans')->ignore($this->input['id'] ?? ''),
            ],
            'start' => ['required', 'date_format:Y-m-d H:i', 'before:end'],
            'end' => ['required', 'date_format:Y-m-d H:i', 'after:start'],
            'venue_id' => ['nullable', 'numeric', 'exists:venues,id'],
            'achievement_id' => ['nullable', 'numeric', 'exists:achievements,id'],
            'published' => ['nullable', 'boolean'],
            'default_event_discord_notification_message' => ['nullable', 'string', 'max:2000'],
        ];

        if (! $this->laravelValidationPasses()) {
            return $this->setValid(false);
        }

        $overlappingLans = Lan::where('start', '<=', $this->input['end'])
            ->where('end', '>=', $this->input['start']);

        // Exclude the current LAN from the overlap check
        if (isset($this->input['id'])) {
            $overlappingLans->whereNotIn('id', [$this->input['id']]);
        }

        if ($overlappingLans->count()) {
            $this->addError(trans('phrase.lans-cannot-overlap'));

            return $this->setValid(false);
        }

        return $this->setValid(true);
    }
}
