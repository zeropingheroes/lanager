<?php

namespace Zeropingheroes\Lanager\Requests;

use Zeropingheroes\Lanager\NavigationLink;

class StoreNavigationLinkRequest extends Request
{
    use LaravelValidation;

    /**
     * Validation rules for the Laravel validator
     *
     * @var array
     */
    protected $laravelValidationRules = [
        'title' => ['required','max:255'],
        'url' => ['nullable','max:2000'],
        'position' => ['integer'],
        'parent_id' => ['nullable','exists:navigation_links,id'],
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
            $this->addError(__('phrase.a-navigation-link-cannot-be-its-own-parent'));
            return $this->setValid(false);
        }

        if (!empty($this->input['parent_id']) && NavigationLink::findOrFail($this->input['parent_id'])->parent_id != null) {
            $this->addError(__('phrase.navigation-links-can-only-be-nested-one-level-deep'));
            return $this->setValid(false);
        }

        return $this->setValid(true);
    }
}