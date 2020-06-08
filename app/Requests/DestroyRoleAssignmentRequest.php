<?php

namespace Zeropingheroes\Lanager\Requests;

use Illuminate\Support\Facades\Auth;
use Zeropingheroes\Lanager\RoleAssignment;

class DestroyRoleAssignmentRequest extends Request
{
    /**
     * Whether the request is valid
     *
     * @return bool
     */
    public function valid(): bool
    {
        $roleAssignment = RoleAssignment::find($this->input['id']);

        if ($roleAssignment->user->id == Auth::user()->id) {
            $this->addError(trans('phrase.cannot-unassign-role-from-self'));
            return $this->setValid(false);
        }

        return $this->setValid(true);
    }
}
