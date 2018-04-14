<?php

namespace Zeropingheroes\Lanager\Requests;

use Zeropingheroes\Lanager\Role;
use Zeropingheroes\Lanager\User;

class StoreRoleAssignmentRequest extends Request
{
    use LaravelValidation;

    /**
     * Validation rules for the Laravel validator
     *
     * @var array
     */
    protected $laravelValidationRules = [
        'user_id' => 'exists:users,id',
        'role_id' => 'exists:roles,id',
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

        $user = User::find($this->input['user_id']);
        $role = Role::find($this->input['role_id']);

        if ($user->hasRole($role->name)) {
            $this->errors->add(
                'user-already-has-role',
                __('phrase.user-already-has-role',
                    [
                        'user' => $user->username,
                        'role' => $role->name,
                    ]
                )
            );
            return $this->setValid(false);
        }

        return $this->setValid(true);
    }
}