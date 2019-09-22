<?php

namespace App\Validators;

use Prettus\Validator\LaravelValidator;
use Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class RoleValidator.
 */
class PermissionValidator extends LaravelValidator
{
    /**
     * Validation Rules.
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [],
        ValidatorInterface::RULE_UPDATE => [],
    ];
}
