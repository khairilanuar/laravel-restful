<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\AuditRepository;
use App\Entities\Audit;
use App\Validators\AuditValidator;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;

/**
 * Class AuditRepositoryEloquent.
 *
 * @package namespace App\Repositories\Eloquent;
 */
class AuditRepositoryEloquent extends BaseRepository implements AuditRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Audit::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {
        return AuditValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
