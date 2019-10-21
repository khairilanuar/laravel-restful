<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\RoleRepository;
use App\Entities\Role;
use Illuminate\Support\Arr;
use Prettus\Repository\Criteria\RequestCriteria;

/**
 * Class RoleRepositoryEloquent.
 */
class RoleRepositoryEloquent extends BaseRepository implements RoleRepository
{
    protected $fieldSearchable = [
        'name' => 'like',
    ];

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Role::class;
    }

    /**
     * Boot up the repository, pushing criteria.
     *
     * @throws
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * @param array $data
     *
     * @throws
     *
     * @return Role
     */
    public function create(array $data): Role
    {
        return \DB::transaction(function () use ($data) {
            $role = new Role(Arr::except($data, 'permissions'));

            if ($role->save()) {
                //event(new RoleCreated($role));
                $role->permissions()->sync(Arr::get($data, 'permissions', []));

                return $role;
            }

            throw new \Exception('Error creating Role');
        });
    }

    /**
     * @param array $data
     * @param $id
     *
     * @throws
     *
     * @return Role
     */
    public function update(array $data, $id): Role
    {
        return \DB::transaction(function () use ($id, $data) {
            if ($role = parent::update(Arr::except($data, 'permissions'), $id)) {
                // update related data
                $role->permissions()->sync($data['permissions']);

                // trigger event
                //event(new RoleUpdated($scheduleStatus));

                return $role;
            }

            throw new \Exception('Error updating Role!');
        });
    }
}
