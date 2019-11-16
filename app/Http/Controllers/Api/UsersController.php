<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\UserRepository;
use App\Entities\User;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Validators\UserValidator;
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
     * @return JsonResponse
     */
    public function show(User $user)
    {
        return $this->sendSuccess($user->load(['roles']), __('api.success_show'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @throws
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
    public function update(UserUpdateRequest $request, User $user)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);
            $_user = $this->repository->update($request->all(), $user->id);

            return $this->sendSuccess($_user, __('api.success_update', ['name' => $this->name]));
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
    public function destroy(User $user)
    {
        if (! $user) {
            $this->sendError('User not found!');
        }

        // TODO: check if deleted user is the last admin user

        $deleted = $this->repository->delete($user->id);

        return $this->sendSuccess($deleted, __('api.success_delete', ['name' => $this->name]));
    }
}
