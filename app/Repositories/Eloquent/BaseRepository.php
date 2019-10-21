<?php

namespace App\Repositories\Eloquent;

abstract class BaseRepository extends \Prettus\Repository\Eloquent\BaseRepository
{
    /**
     * Load relations.
     *
     * @param array|string $relations
     *
     * @return \Prettus\Repository\Eloquent\BaseRepository
     */
    public function without($relations)
    {
        $this->model = $this->model->without($relations);

        return $this;
    }
}
