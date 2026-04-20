<?php

namespace Zeropingheroes\Lanager\Requests;

use Illuminate\Support\Facades\Storage;

class UpdateImageRequest extends Request
{
    use LaravelValidation;

    /**
     * {@inheritDoc}
     */
    #[\Override]
    public function valid(): bool
    {
        if (! Storage::exists($this->input['original_file_path'])) {
            abort(404);
        }

        $this->validationRules = [
            'new_filename_without_extension' => ['required', 'alpha_dash'],
        ];

        if (! $this->laravelValidationPasses()) {
            return $this->setValid(false);
        }

        if (Storage::exists($this->input['new_file_path'])) {
            $this->addError(trans('phrase.image-already-exists'));

            return $this->setValid(false);
        }

        return $this->setValid(true);
    }
}
