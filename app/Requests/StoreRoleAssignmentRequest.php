<?php

namespace Zeropingheroes\Lanager\Requests;

use Zeropingheroes\Lanager\Role;
use Zeropingheroes\Lanager\User;

class StoreRoleAssignmentRequest extends Request implements RequestContract
{
    protected $rules = [
        'user_id' => 'exists:users,id',
        'role_id' => 'exists:roles,id',
    ];

    public function valid(): bool
    {
        if ($this->validator->fails()) {
            return $this->setValid(false);
        }

        $user = User::find($this->input['user_id']);
        $role = Role::find($this->input['role_id']);

        if ($user->hasRole($role->name)) {
            $this->validator->errors()->add(
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