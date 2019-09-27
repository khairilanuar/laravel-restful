<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Audit.
 */
class Audit extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = 'audits';

    protected $fillable = [];

    protected $appends = ['module'];

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'full_name' => '(System)',
        ]);
    }

    public function auditable()
    {
        return $this->morphTo()->withDefault();
    }

    public function getModuleAttribute()
    {
        return class_basename($this->auditable_type);
    }
}
