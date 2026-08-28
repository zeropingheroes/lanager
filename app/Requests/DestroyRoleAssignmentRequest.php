<?php

declare(strict_types=1);

namespace Zeropingheroes\Lanager\Requests;

use Illuminate\Support\Facades\Auth;
use Zeropingheroes\Lanager\Models\RoleAssignment;

class DestroyRoleAssignmentRequest extends Request
{
    /**
     * {@inheritDoc}
     */
    #[\Override]
    public function valid(): bool
    {
        $roleAssignment = RoleAssignment::find($this->input['id']);

        if ($roleAssignment->user->id == Auth::user()->id) {
            $this->addError(trans('phrase.cannot-change-own-role-assignments'));

            return $this->setValid(false);
        }

        return $this->setValid(true);
    }
}
