<?php

namespace App\Repositories\Eloquent;

use App\Entities\Permission;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Contracts\Repositories\PermissionRepository;

/**
 * Class PermissionRepositoryEloquent.
 */
class PermissionRepositoryEloquent extends BaseRepository implements PermissionRepository
{
    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Permission::class;
    }

    /**
     * Boot up the repository, pushing criteria.
     * @throws
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * @param array $data
     * @throws
     * @return Permission
     */
    public function create(array $data) : Permission
    {
        return \DB::transaction(function () use ($data) {
            $permission = new Permission($data);

            if ($permission->save()) {
                //event(new PermissionCreated($permission));

                return $permission;
            }

            throw new \Exception('Error creating Permission');
        });
    }

    /**
     * @param array $data
     * @param $id
     * @throws
     * @return Permission
     */
    public function update(array $data, $id) : Permission
    {
        return \DB::transaction(function () use ($id, $data) {
            if ($permission = parent::update($data, $id)) {
                // update related data

                // trigger event
                //event(new PermissionUpdated($scheduleStatus));

                return $permission;
            }

            throw new \Exception('Error updating Permission!');
        });
    }
}
