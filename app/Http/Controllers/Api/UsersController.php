<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\UserRepository;
use App\Entities\User;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Validators\UserValidator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class UsersController.
 */
class UsersController extends BaseController
{
    protected $name = 'user';

    /**
     * @var UserRepository
     */
    protected $repository;

    /**
     * @var UserValidator
     */
    protected $validator;

    /**
     * UsersController constructor.
     *
     * @param UserRepository $repository
     * @param UserValidator  $validator
     */
    public function __construct(UserRepository $repository, UserValidator $validator)
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
        $per_page = \Request::get('perPage');
        $data = $this->repository->with(['roles'])->paginate($per_page);

        return $this->sendSuccess($data, __('api.success_list', ['name' => $this->name]));
    }

    /**
     * Display the specified resource.
     *
     * @param string $user
     *
     * @return JsonResponse
     */
    public function show(string $user)
    {
        try {
            if (! $user = $this->repository->findWhere(['uuid' => $user])->first()) {
                throw new ModelNotFoundException();
            }

            return $this->sendSuccess($user->load(['roles']), __('api.success_show'));
        } catch (ModelNotFoundException $e) {
            return $this->sendError(__('api.error_not_found', ['name' => $this->name]), $e->getMessage());
        } catch (\Throwable $e) {
            \Log::error($e->getMessage(), $e->getTrace());
            return $this->sendError(__('api.error_general', ['name' => $this->name]), $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserCreateRequest $request
     *
     * @return JsonResponse
     */
    public function store(UserCreateRequest $request)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
            $user = $this->repository->create($request->all());

            return $this->sendSuccess($user, __('api.success_create', ['name' => $this->name]));
        } catch (ValidatorException $e) {
            return $this->sendError(__('api.error_validation'), $e->getMessageBag(), 400);
        } catch (QueryException $e) {
            return $this->sendError(__('api.error_query'), $e->getMessage(), 400);
        } catch (\Throwable $e) {
            return $this->sendError(__('api.error_general'), $e->getMessage(), 400);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserUpdateRequest $request
     * @param string            $user
     *
     * @return JsonResponse
     */
    public function update(UserUpdateRequest $request, string $user)
    {
        try {
            if (! $user = $this->repository->findWhere(['uuid' => $user])->first()) {
                throw new ModelNotFoundException();
            }
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);
            $updated = $this->repository->update($request->all(), $user->id);

            return $this->sendSuccess($updated, __('api.success_update', ['name' => $this->name]));
        } catch (ValidatorException $e) {
            return $this->sendError(__('api.error_validation'), $e->getMessageBag());
        } catch (QueryException $e) {
            return $this->sendError(__('api.error_query'), $e->getMessage(), 400);
        } catch (ModelNotFoundException $e) {
            return $this->sendError(__('api.error_not_found', ['name' => $this->name]), $e->getMessage());
        } catch (\Throwable $e) {
            \Log::error($e->getMessage(), $e->getTrace());
            return $this->sendError(__('api.error_general', ['name' => $this->name]), $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $user
     *
     * @return JsonResponse
     */
    public function destroy(string $user)
    {
        try {
            if (! $user = $this->repository->findWhere(['uuid' => $user])->first()) {
                throw new ModelNotFoundException();
            }

            // TODO: check if deleted user is the last admin user

            $deleted = $this->repository->delete($user->id);

            return $this->sendSuccess($deleted, __('api.success_delete', ['name' => $this->name]));
        } catch (ModelNotFoundException $e) {
            return $this->sendError(__('api.error_not_found', ['name' => $this->name]), $e->getMessage());
        } catch (\Throwable $e) {
            \Log::error($e->getMessage(), $e->getTrace());
            return $this->sendError(__('api.error_general', ['name' => $this->name]), $e->getMessage());
        }
    }
}
