<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\RoleRepository;
use App\Entities\Role;
use App\Http\Requests\RoleCreateRequest;
use App\Http\Requests\RoleUpdateRequest;
use App\Validators\RoleValidator;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class RolesController.
 */
class RolesController extends BaseController
{
    protected $name = 'role';

    /**
     * @var RoleRepository
     */
    protected $repository;

    /**
     * @var RoleValidator
     */
    protected $validator;

    /**
     * RolesController constructor.
     *
     * @param RoleRepository $repository
     * @param RoleValidator  $validator
     */
    public function __construct(RoleRepository $repository, RoleValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        if (\Request::get('getRef', false)) {
            return $this->getRef();
        }

        $per_page = \Request::get('perPage');
        $data = $this->repository
            ->with(['permissions:id,name,label'])
            ->paginate($per_page);

        return $this->sendSuccess($data, __('api.success_list', ['name' => $this->name]));
    }

    protected function getRef()
    {
        $refs = $this->repository->makeModel()
            ->orderBy('name')
            ->get(['name as text', 'id as value']);

        return $this->sendSuccess($refs, __('api.success_list', ['name' => $this->name]));
    }

    /**
     * Display the specified resource.
     *
     * @param Role $role
     *
     * @return JsonResponse
     */
    public function show(Role $role)
    {
        return $this->sendSuccess(
            $role->load(['permissions:id,name,label']),
            __('api.success_show', ['name' => $this->name])
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param RoleCreateRequest $request
     *
     * @return JsonResponse
     */
    public function store(RoleCreateRequest $request)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
            $role = $this->repository->create($request->all());

            return $this->sendSuccess($role, __('api.success_create', ['name' => $this->name]));
        } catch (ValidatorException $e) {
            return $this->sendError(__('api.error_validation'), $e->getMessageBag(), 400);
        } catch (QueryException $e) {
            return $this->sendError(__('api.error_query'), $e->getMessage(), 400);
        } catch (\Exception $e) {
            return $this->sendError(__('api.error_general'), $e->getMessage(), 400);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param RoleUpdateRequest $request
     * @param Role              $role
     *
     * @return JsonResponse
     */
    public function update(RoleUpdateRequest $request, Role $role)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);
            $_role = $this->repository->update($request->all(), $role->id);

            return $this->sendSuccess($_role, __('api.success_update', ['name' => $this->name]));
        } catch (ValidatorException $e) {
            return $this->sendError(__('api.error_validation'), $e->getMessageBag());
        } catch (QueryException $e) {
            return $this->sendError(__('api.error_query'), $e->getMessage(), 400);
        } catch (\Exception $e) {
            return $this->sendError(__('api.error_general'), $e->getMessage(), 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Role $role
     *
     * @return JsonResponse
     */
    public function destroy(Role $role)
    {
        $deleted = $this->repository->delete($role->id);

        return $this->sendSuccess($deleted, __('api.success_delete', ['name' => $this->name]));
    }
}
