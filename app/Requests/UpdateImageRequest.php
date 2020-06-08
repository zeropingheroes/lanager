<?php

namespace Zeropingheroes\Lanager\Requests;

use Storage;

class UpdateImageRequest extends Request
{
    use LaravelValidation;

    /**
     * Whether the request is valid
     *
     * @return bool
     */
    public function valid(): bool
    {
        if (!Storage::exists($this->input['original_file_path'])) {
            abort(404);
            return $this->setValid(false);
        }

        $this->validationRules = [
            'new_filename_without_extension' => ['required', 'alpha_dash'],
        ];

        if (!$this->laravelValidationPasses()) {
            return $this->setValid(false);
        }

        if (Storage::exists($this->input['new_file_path'])) {
            $this->addError(trans('phrase.image-already-exists'));
            return $this->setValid(false);
        }

        return $this->setValid(true);
    }
}
