<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\UserRepository;
use App\Entities\User;
use Illuminate\Support\Arr;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class UserRepositoryEloquent.
 */
class UserRepositoryEloquent extends BaseRepository implements UserRepository
{
    protected $fieldSearchable = [
        'first_name' => 'like',
        'last_name'  => 'like',
        'email'      => 'like',
    ];

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return User::class;
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
     * @throws
     */
    public function create(array $data): User
    {
        return \DB::transaction(function () use ($data) {
            $user = new User(Arr::except($data, 'roles'));

            if ($user->save()) {
                $user->syncRoles(Arr::get($data, 'roles', []));
                //event(new UserCreated($user));
                return $user;
            }

            throw new \Exception('Error creating User');
        });
    }

    /**
     * @param $id
     *
     * @throws \Throwable
     */
    public function update(array $data, $id): User
    {
        return \DB::transaction(function () use ($id, $data) {
            if (! Arr::get($data, 'password')) {
                // if password unchanged, remove it from array
                unset($data['password']);
            } else {
                // else hash the password
                $data['password'] = \Hash::make($data['password']);
            }

            if ($user = parent::update(Arr::except($data, ['roles']), $id)) {
                // update related data
                $roles = Arr::get($data, 'roles', []);
                if ($roles && \is_array($roles) && \is_array($roles[0])) {
                    $_roles = [];
                    foreach ($roles as $role) {
                        $_roles[] = Arr::get($role, 'name');
                    }
                    $roles = $_roles;
                }
                $user->syncRoles($roles);

                // trigger event
                //event(new UserUpdated($scheduleStatus));

                return $user;
            }

            throw new \Exception('Error updating User!');
        });
    }
}
