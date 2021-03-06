<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\PermissionRepository;
use App\Entities\Permission;
use App\Http\Requests\PermissionCreateRequest;
use App\Http\Requests\PermissionUpdateRequest;
use App\Validators\PermissionValidator;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class PermissionsController.
 */
class PermissionsController extends BaseController
{
    protected $name = 'Permission';

    /**
     * @var PermissionRepository
     */
    protected $repository;

    /**
     * @var PermissionValidator
     */
    protected $validator;

    /**
     * RolesController constructor.
     */
    public function __construct(PermissionRepository $repository, PermissionValidator $validator)
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
        if ($ref = \Request::get('getRef')) {
            return $this->getRef($ref);
        }

        $per_page = \Request::get('perPage');
        $data = $this->repository->paginate($per_page);

        return $this->sendSuccess($data, __('api.success_list', ['name' => $this->name]));
    }

    protected function getRef($ref = null)
    {
        $refs = $this->repository->makeModel()
            ->defaultOrder()
            ->get()
            ->toTree()
            ->toArray();

        return $this->sendSuccess($refs, __('api.success_list', ['name' => $this->name]));
    }

    /**
     * Display the specified resource.
     *
     * @return JsonResponse
     */
    public function show(Permission $permission)
    {
        return $this->sendSuccess($permission->load(['tenant']), __('api.success_show'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @throws
     *
     * @return JsonResponse
     */
    public function store(PermissionCreateRequest $request)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
            $permission = $this->repository->create($request->all());

            return $this->sendSuccess($permission, __('api.success_create', ['name' => $this->name]));
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
     * @throws
     *
     * @return JsonResponse
     */
    public function update(PermissionUpdateRequest $request, Permission $permission)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);
            $_permission = $this->repository->update($request->all(), $permission->id);

            return $this->sendSuccess($_permission, __('api.success_update', ['name' => $this->name]));
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
     * @return JsonResponse
     */
    public function destroy(Permission $permission)
    {
        $deleted = $this->repository->delete($permission->id);

        return $this->sendSuccess($deleted, __('api.success_delete', ['name' => $this->name]));
    }
}
