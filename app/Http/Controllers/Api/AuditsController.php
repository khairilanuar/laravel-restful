<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\AuditRepository;
use App\Entities\Audit;
use App\Entities\User;
use App\Validators\AuditValidator;
use Illuminate\Http\JsonResponse;

/**
 * Class AuditsController.
 */
class AuditsController extends BaseController
{
    protected $name = 'audit';

    /**
     * @var AuditRepository
     */
    protected $repository;

    /**
     * @var AuditValidator
     */
    protected $validator;

    /**
     * RolesController constructor.
     *
     * @param AuditRepository $repository
     * @param AuditValidator  $validator
     */
    public function __construct(AuditRepository $repository, AuditValidator $validator)
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
        if (\Request::get('getRef')) {
            return $this->getRef();
        }

        $per_page = \Request::get('perPage');
        $data = $this->repository->with(['auditable', 'user'])
            // ->orderBy('created_at', 'desc')
            ->paginate($per_page);

        return $this->sendSuccess($data, __('api.success_list', ['name' => $this->name]));
    }

    protected function getRef()
    {
        $refs = $this->repository->makeModel();
        $return = [];

        // modules (auditable_type)
        $return['modules'] = $refs->orderBy('auditable_type')
            ->distinct('auditable_type')
            ->get(['auditable_type as text', 'auditable_type as value']);

        // users
        foreach (User::all() as $user) {
            $return['users'][] = ['text' => $user->full_name, 'value' => $user->id];
        }

        return $this->sendSuccess($return, __('api.success_list'));
    }

    /**
     * Display the specified resource.
     *
     * @param Audit $audit
     *
     * @return JsonResponse
     */
    public function show(Audit $audit)
    {
        return $this->sendSuccess($audit, __('api.success_show'));
    }
}
