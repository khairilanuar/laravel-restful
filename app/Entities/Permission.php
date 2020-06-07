<?php

namespace App\Entities;

use Kalnoy\Nestedset\NodeTrait;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableInterface;

/**
 * Class Permission.
 */
class Permission extends \Spatie\Permission\Models\Permission implements AuditableInterface
{
    use NodeTrait;
    use Auditable;
}
