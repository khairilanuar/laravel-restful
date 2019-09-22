<?php

namespace App\Entities;

use OwenIt\Auditing\Auditable;
use App\Entities\Traits\Methods\RoleMethod;
use Spatie\Permission\Models\Role as SpatieRole;
use App\Entities\Traits\Attributes\RoleAttribute;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

/**
 * Class Role.
 */
class Role extends SpatieRole implements AuditableContract
{
    use Auditable,
        RoleAttribute,
        RoleMethod;

    /**
     * Attributes to exclude from the Audit.
     *
     * @var array
     */
    protected $auditExclude = [
        'id',
    ];

    protected $casts = [
        'enable' => 'boolean',
        'is_core' => 'boolean',
    ];
}
