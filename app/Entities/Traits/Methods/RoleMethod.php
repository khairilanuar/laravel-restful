<?php

namespace App\Entities\Traits\Methods;

/**
 * Trait RoleMethod.
 */
trait RoleMethod
{
    /**
     * @return mixed
     */
    public function isAdmin()
    {
        return $this->name === config('access.users.admin_role');
    }
}
