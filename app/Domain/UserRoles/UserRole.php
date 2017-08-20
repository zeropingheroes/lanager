<?php namespace Zeropingheroes\Lanager\Domain\UserRoles;

use Zeropingheroes\Lanager\Domain\BaseModel;

class UserRole extends BaseModel
{

    protected $table = 'user_roles';

    protected $fillable = ['user_id', 'role_id', 'assigned_by'];

    /**
     * A single user role (assignment) belongs to a single user
     * @return object Illuminate\Database\Eloquent\Relations\Relation
     */
    public function user()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\Domain\Users\User');
    }

    /**
     * A single user role (assignment) belongs to a single role
     * @return object Illuminate\Database\Eloquent\Relations\Relation
     */
    public function role()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\Domain\Roles\Role');
    }

    /**
     * A single user role (assignment) belongs to a single user (who assigned the role)
     * @return object Illuminate\Database\Eloquent\Relations\Relation
     */
    public function assignedBy()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\Domain\Users\User', 'assigned_by');
    }

}
