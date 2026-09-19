<?php

declare(strict_types=1);

namespace Zeropingheroes\Lanager\Requests;

use Carbon\Carbon;
use Zeropingheroes\Lanager\Models\Lan;

class StoreSlideRequest extends Request
{
    use LaravelValidation;

    /**
     * {@inheritDoc}
     */
    #[\Override]
    public function valid(): bool
    {
        $this->validationRules = [
            'lan_id' => ['required', 'numeric', 'exists:lans,id'],
            'name' => ['required', 'max:255'],
            'content' => ['required'],
            'position' => ['required', 'integer', 'min:0', 'max:127'],
            'duration' => ['required', 'integer', 'min:0', 'max:32767'],
            'start' => ['nullable', 'date_format:Y-m-d H:i', 'before:end'],
            'end' => ['nullable', 'date_format:Y-m-d H:i', 'after:start'],
            'published' => ['boolean'],
        ];

        if (! $this->laravelValidationPasses()) {
            return $this->setValid(false);
        }

        $lan = Lan::findOrFail($this->input['lan_id']);

        foreach (['start', 'end'] as $field) {
            if (
                filled($this->input[$field] ?? null)
                && ! Carbon::make($this->input[$field])->between($lan->start, $lan->end)
            ) {
                $this->addError(trans('phrase.slide-times-must-be-within-lan-times'));

                return $this->setValid(false);
            }
        }

        return $this->setValid(true);
    }
}
