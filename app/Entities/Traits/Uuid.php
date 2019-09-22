<?php

namespace App\Entities\Traits;

use Ramsey\Uuid\Uuid as PackageUuid;

/**
 * Trait Uuid.
 */
trait Uuid
{
    protected $uuidName = 'uuid';

    protected $useUuidKeyName = true;

    /**
     * @param $query
     * @param $uuid
     *
     * @return mixed
     */
    public function scopeUuid($query, $uuid)
    {
        return $query->where($this->getUuidName(), $uuid);
    }

    /**
     * @return string
     */
    public function getUuidName()
    {
        return property_exists($this, 'uuidName') ? $this->uuidName : 'uuid';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getUuidName()} = PackageUuid::uuid4()->toString();
        });
    }

    public function getRouteKeyName()
    {
        return $this->useUuidKeyName ? $this->getUuidName() : $this->getKeyName();
    }
}
