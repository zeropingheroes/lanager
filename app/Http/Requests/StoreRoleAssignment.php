<?php

namespace Zeropingheroes\Lanager\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Zeropingheroes\Lanager\RoleAssignment;

class StoreRoleAssignment extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', RoleAssignment::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => 'exists:users,id',
            'role_id' => 'exists:roles,id',
        ];
    }
}
