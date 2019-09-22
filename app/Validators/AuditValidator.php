<?php

namespace App\Validators;

use \Prettus\Validator\LaravelValidator;
use \Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class AuditValidator.
 *
 * @package namespace App\Validators;
 */
class AuditValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        // TODO: validation rules for Audit entity
        ValidatorInterface::RULE_CREATE => [],
        ValidatorInterface::RULE_UPDATE => [],
    ];
}
