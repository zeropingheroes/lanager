<?php

declare(strict_types=1);

namespace Zeropingheroes\Lanager\Requests;

use Zeropingheroes\Lanager\Models\NavigationLink;

class StoreNavigationLinkRequest extends Request
{
    use LaravelValidation;

    /**
     * {@inheritDoc}
     */
    #[\Override]
    public function valid(): bool
    {
        $this->validationRules = [
            'title' => ['required', 'max:255'],
            'url' => ['nullable', 'max:2000'],
            'position' => ['required', 'integer'],
            'parent_id' => ['nullable', 'exists:navigation_links,id'],
        ];

        if (! $this->laravelValidationPasses()) {
            return $this->setValid(false);
        }

        if (! empty($this->input['parent_id'])) {
            if (NavigationLink::findOrFail($this->input['parent_id'])->parent_id != null) {
                $this->addError(trans('phrase.navigation-links-can-only-be-nested-one-level-deep'));

                return $this->setValid(false);
            }
        }

        if (! empty($this->input['parent_id']) && ! empty($this->input['id'])) {
            if ($this->input['parent_id'] == $this->input['id']) {
                $this->addError(trans('phrase.a-navigation-link-cannot-be-its-own-parent'));

                return $this->setValid(false);
            }
        }

        return $this->setValid(true);
    }
}
