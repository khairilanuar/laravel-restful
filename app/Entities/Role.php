<?php

namespace App\Entities;

use App\Entities\Traits\Attributes\RoleAttribute;
use App\Entities\Traits\Methods\RoleMethod;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Spatie\Permission\Models\Role as SpatieRole;

/**
 * Class Role.
 */
class Role extends SpatieRole implements AuditableContract
{
    use Auditable;
    use
        RoleAttribute;
    use
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
        'enable'  => 'boolean',
        'is_core' => 'boolean',
    ];
}
