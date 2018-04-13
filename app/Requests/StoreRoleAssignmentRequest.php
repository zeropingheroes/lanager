<?php

namespace Zeropingheroes\Lanager\Requests;

use Illuminate\Support\Facades\Validator;
use Zeropingheroes\Lanager\Role;
use Zeropingheroes\Lanager\User;

class StoreRoleAssignmentRequest extends Request
{
    /**
     * Validation rules for the built in validator
     *
     * @var array
     */
    protected $rules = [
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
        // TODO: Refactor calls to Laravel validator into trait, as it will be used frequently
        $validator = Validator::make($this->input, $this->rules);

        if ($validator->fails()) {
            $this->errors = $validator->errors();
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