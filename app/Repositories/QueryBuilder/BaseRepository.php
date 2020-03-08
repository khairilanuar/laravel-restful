<?php

namespace App\Repositories\QueryBuilder;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\Presentable;
use Prettus\Repository\Contracts\PresenterInterface;
use Prettus\Repository\Contracts\RepositoryCriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

abstract class BaseRepository implements RepositoryInterface, RepositoryCriteriaInterface
{
    protected $builder;

    protected $relations = [];

    /**
     * @var PresenterInterface
     */
    protected $presenter;

    /**
     * @var bool
     */
    protected $skipPresenter = false;

    public function __construct()
    {
        $this->makeBuilder();
    }

    /**
     * Specify table name for builder.
     *
     * @return string
     */
    abstract public function tableName();

    public function makeBuilder()
    {
        $this->builder = \DB::table($this->tableName());
    }

    /**
     * {@inheritdoc}
     */
    public function lists($column, $key = null)
    {
        // TODO: Implement lists() method.
    }

    /**
     * {@inheritdoc}
     */
    public function pluck($column, $key = null)
    {
        // TODO: Implement pluck() method.
    }

    /**
     * {@inheritdoc}
     */
    public function sync($id, $relation, $attributes, $detaching = true)
    {
        // TODO: Implement sync() method.
    }

    /**
     * {@inheritdoc}
     */
    public function syncWithoutDetaching($id, $relation, $attributes)
    {
        // TODO: Implement syncWithoutDetaching() method.
    }

    /**
     * {@inheritdoc}
     */
    public function all($columns = ['*'])
    {
        // TODO: Implement all() method.
    }

    /**
     * {@inheritdoc}
     */
    public function paginate($limit = null, $columns = ['*'])
    {
        $limit = null === $limit ? config('repository.pagination.limit', 15) : $limit;
        $results = $this->builder->paginate($limit, $columns);
        $results->appends(app('request')->query());
        $this->makeBuilder();

        return $this->parserResult($results);
    }

    /**
     * {@inheritdoc}
     */
    public function simplePaginate($limit = null, $columns = ['*'])
    {
        // TODO: Implement simplePaginate() method.
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function find($id, $columns = ['*'])
    {
        $model = $this->builder->find($id, $columns);
        $this->makeBuilder();

        if (! $model) {
            throw new \Exception('Not found');
        }

        return $this->parserResult($model);
    }

    /**
     * {@inheritdoc}
     */
    public function findByField($field, $value, $columns = ['*'], $firstOnly = false)
    {
        $this->builder->where($field, '=', $value);
        $model = $firstOnly ? $this->builder->first($columns) : $this->builder->get($columns);
        $this->makeBuilder();

        return $this->parserResult($model);
    }

    public function findFirstByField($field, $value, $columns = ['*'])
    {
        return $this->findByField($field, $value, $columns, true);
    }

    /**
     * {@inheritdoc}
     */
    public function findWhere(array $where, $columns = ['*'], $firstOnly = false)
    {
        $this->applyConditions($where);

        $model = $firstOnly ? $this->builder->first($columns) : $this->builder->get($columns);
        $this->makeBuilder();

        return $this->parserResult($model);
    }

    /**
     * Find first record by multiple fields.
     *
     * @param array $columns
     *
     * @return mixed
     */
    public function findFirstWhere(array $where, $columns = ['*'])
    {
        return $this->findWhere($where, $columns, true);
    }

    /**
     * {@inheritdoc}
     */
    public function findWhereIn($field, array $values, $columns = ['*'])
    {
        // TODO: Implement findWhereIn() method.
    }

    /**
     * {@inheritdoc}
     */
    public function findWhereNotIn($field, array $values, $columns = ['*'])
    {
        // TODO: Implement findWhereNotIn() method.
    }

    /**
     * {@inheritdoc}
     */
    public function findWhereBetween($field, array $values, $columns = ['*'])
    {
        // TODO: Implement findWhereBetween() method.
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $attributes)
    {
        // TODO: Implement create() method.
    }

    /**
     * {@inheritdoc}
     */
    public function update(array $attributes, $id)
    {
        // TODO: Implement update() method.
    }

    /**
     * {@inheritdoc}
     */
    public function updateOrCreate(array $attributes, array $values = [])
    {
        // TODO: Implement updateOrCreate() method.
    }

    /**
     * {@inheritdoc}
     */
    public function delete($id)
    {
        // TODO: Implement delete() method.
    }

    /**
     * {@inheritdoc}
     */
    public function orderBy($column, $direction = 'asc')
    {
        // TODO: Implement orderBy() method.
    }

    /**
     * {@inheritdoc}
     */
    public function with($relations)
    {
        // TODO: Implement with() method.
    }

    /**
     * {@inheritdoc}
     */
    public function whereHas($relation, $closure)
    {
        // TODO: Implement whereHas() method.
    }

    /**
     * {@inheritdoc}
     */
    public function withCount($relations)
    {
        // TODO: Implement withCount() method.
    }

    /**
     * {@inheritdoc}
     */
    public function hidden(array $fields)
    {
        // TODO: Implement hidden() method.
    }

    /**
     * {@inheritdoc}
     */
    public function visible(array $fields)
    {
        // TODO: Implement visible() method.
    }

    /**
     * {@inheritdoc}
     */
    public function scopeQuery(\Closure $scope)
    {
        // TODO: Implement scopeQuery() method.
    }

    /**
     * {@inheritdoc}
     */
    public function resetScope()
    {
        // TODO: Implement resetScope() method.
    }

    /**
     * {@inheritdoc}
     */
    public function getFieldsSearchable()
    {
        // TODO: Implement getFieldsSearchable() method.
    }

    /**
     * {@inheritdoc}
     */
    public function setPresenter($presenter)
    {
        // TODO: Implement setPresenter() method.
    }

    /**
     * {@inheritdoc}
     */
    public function skipPresenter($status = true)
    {
        // TODO: Implement skipPresenter() method.
    }

    /**
     * {@inheritdoc}
     */
    public function firstOrNew(array $attributes = [])
    {
        // TODO: Implement firstOrNew() method.
    }

    /**
     * {@inheritdoc}
     */
    public function firstOrCreate(array $attributes = [])
    {
        // TODO: Implement firstOrCreate() method.
    }

    /**
     * {@inheritdoc}
     */
    public static function __callStatic($method, $arguments)
    {
        // TODO: Implement __callStatic() method.
    }

    /**
     * {@inheritdoc}
     */
    public function __call($method, $arguments)
    {
        // TODO: Implement __call() method.
    }

    /**
     * {@inheritdoc}
     */
    public function pushCriteria($criteria)
    {
        // TODO: Implement pushCriteria() method.
    }

    /**
     * {@inheritdoc}
     */
    public function popCriteria($criteria)
    {
        // TODO: Implement popCriteria() method.
    }

    /**
     * {@inheritdoc}
     */
    public function getCriteria()
    {
        // TODO: Implement getCriteria() method.
    }

    /**
     * {@inheritdoc}
     */
    public function getByCriteria(CriteriaInterface $criteria)
    {
        // TODO: Implement getByCriteria() method.
    }

    /**
     * {@inheritdoc}
     */
    public function skipCriteria($status = true)
    {
        // TODO: Implement skipCriteria() method.
    }

    /**
     * {@inheritdoc}
     */
    public function resetCriteria()
    {
        // TODO: Implement resetCriteria() method.
    }

    /**
     * Applies the given where conditions to the builder.
     *
     * @return void
     */
    protected function applyConditions(array $where)
    {
        foreach ($where as $field => $value) {
            if (\is_array($value)) {
                list($field, $condition, $val) = $value;
                $this->builder = $this->builder->where($field, $condition, $val);
            } else {
                $this->builder = $this->builder->where($field, '=', $value);
            }
        }
    }

    /**
     * Wrapper result data.
     *
     * @param mixed $result
     *
     * @return mixed
     */
    protected function parserResult($result)
    {
        if ($this->presenter instanceof PresenterInterface) {
            if ($result instanceof Collection || $result instanceof LengthAwarePaginator) {
                $result->each(function ($model) {
                    if ($model instanceof Presentable) {
                        $model->setPresenter($this->presenter);
                    }

                    return $model;
                });
            } elseif ($result instanceof Presentable) {
                $result = $result->setPresenter($this->presenter);
            }

            if (! $this->skipPresenter) {
                return $this->presenter->present($result);
            }
        }

        return $result;
    }
}
