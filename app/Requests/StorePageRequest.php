<?php

namespace Zeropingheroes\Lanager\Requests;

class StorePageRequest extends Request
{
    use LaravelValidation;

    /**
     * Validation rules for the Laravel validator
     *
     * @var array
     */
    protected $laravelValidationRules = [
        'title' => 'required|max:255',
        'content' => 'nullable',
        'parent_id' => 'nullable|exists:pages,id',
    ];

    /**
     * Whether the request is valid
     *
     * @return bool
     */
    public function valid(): bool
    {
        if (!$this->laravelValidationPasses()) {
            return $this->setValid(false);
        }

        if (!empty($this->input['parent_id']) && isset($this->input['id']) && $this->input['id'] == $this->input['parent_id']) {
            $this->addError(__('phrase.a-page-cannot-be-its-own-parent'));
            return $this->setValid(false);
        }

        return $this->setValid(true);
    }
}