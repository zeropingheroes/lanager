<?php

namespace Zeropingheroes\Lanager\Requests;

use Auth;
use Zeropingheroes\Lanager\Models\Role;
use Zeropingheroes\Lanager\Models\User;

class StoreRoleAssignmentRequest extends Request
{
    use LaravelValidation;

    /**
     * Whether the request is valid.
     *
     * @return bool
     */
    public function valid(): bool
    {
        $this->validationRules = [
            'user_id' => ['required', 'exists:users,id'],
            'role_id' => ['required', 'exists:roles,id'],
        ];

        if (! $this->laravelValidationPasses()) {
            return $this->setValid(false);
        }

        $user = User::find($this->input['user_id']);
        $role = Role::find($this->input['role_id']);

        if ($user->hasRole($role->name)) {
            $this->addError(
                trans('phrase.user-already-has-role', ['user' => $user->username, 'role' => $role->display_name])
            );

            return $this->setValid(false);
        }

        if ($user == Auth::user()) {
            $this->addError(trans('phrase.cannot-change-own-role-assignments'));

            return $this->setValid(false);
        }

        if ($user->hasRole('super-admin')) {
            $this->addError(trans('phrase.cannot-assign-role-to-super-admin'));

            return $this->setValid(false);
        }

        return $this->setValid(true);
    }
}
