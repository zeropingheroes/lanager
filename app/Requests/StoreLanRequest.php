<?php

namespace Zeropingheroes\Lanager\Requests;

use Zeropingheroes\Lanager\Lan;

class StoreLanRequest extends Request
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
            'name' => ['required', 'max:255'],
            'description' => ['nullable'],
            'start' => ['required', 'date_format:Y-m-d H:i:s', 'before:end'],
            'end' => ['required', 'date_format:Y-m-d H:i:s', 'after:start'],
            'venue_id' => ['nullable', 'numeric', 'exists:venues,id'],
            'achievement_id' => ['nullable', 'numeric','exists:achievements,id'],
            'published' => ['nullable', 'boolean']
        ];

        if (!$this->laravelValidationPasses()) {
            return $this->setValid(false);
        }

        $overlappingLans = Lan::where('start', '<=', $this->input['end'])
            ->where('end', '>=', $this->input['start']);

        // Exclude the current LAN from the overlap check
        if (isset($this->input['id'])) {
            $overlappingLans->whereNotIn('id', [$this->input['id']]);
        }

        if ($overlappingLans->count()) {
            $this->addError(__('phrase.lans-cannot-overlap'));
            return $this->setValid(false);
        }

        return $this->setValid(true);
    }
}