<?php namespace Zeropingheroes\Lanager\Domain\UserRoles;

use League\Fractal\TransformerAbstract;
use Zeropingheroes\Lanager\Domain\Roles\RoleTransformer;
use Zeropingheroes\Lanager\Domain\Users\UserTransformer;

class UserRoleTransformer extends TransformerAbstract
{

    /**
     * Default related resources to include in transformed output
     * @var array
     */
    protected $defaultIncludes = [
        'user',
        'role',
    ];

    /**
     * Transform resource into standard output format with correct typing
     * @param  object BaseModel   Resource being transformed
     * @return array              Transformed object array ready for output
     */
    public function transform(UserRole $userRole)
    {
        return [
            'id' => (int)$userRole->id,
            'user_id' => (int)$userRole->user_id,
            'role_id' => (int)$userRole->role_id,
            'links' => [
                [
                    'rel' => 'self',
                    'uri' => (url().'/user-roles/'.$userRole->id),
                ],
            ],
        ];
    }

    /**
     * Pull in and transform the specified resource
     * @param  object BaseModel   Model being pulled in
     * @return array              Transformed model
     */
    public function includeUser(UserRole $userRole)
    {
        return $this->item($userRole->user()->first(), new UserTransformer);
    }

    /**
     * Pull in and transform the specified resource
     * @param  object BaseModel   Model being pulled in
     * @return array              Transformed model
     */
    public function includeRole(UserRole $userRole)
    {
        return $this->item($userRole->role()->first(), new RoleTransformer);
    }
}