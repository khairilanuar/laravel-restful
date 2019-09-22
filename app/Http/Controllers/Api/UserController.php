<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;
use App\Entities\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends BaseController
{
    public function profile(Request $request)
    {
        $return = [
            'user' => \Auth::user()->load('paymeInstance'),
            'permissions' => $this->getPermissions(),
            'roles' => $this->getRoles(),
            'apps' => \Auth::user()->apps
        ];

        return $this->sendSuccess($return, 'Ok.');
    }

    public function logout(Request $request)
    {
        try {
            $accessTokenHeader = $request->header('Authorization');
            $accessToken = explode(' ', $accessTokenHeader)[1];
            $accessToken = explode('.', $accessToken)[0];
            $accessToken = json_decode(base64_decode($accessToken))->jti;

            // delete accessToken
            Auth::user()->tokens()->where('id', '=', $accessToken)->delete();

            return $this->sendSuccess([], 'Ok.');
        } catch (\Exception $e) {
            return $this->sendError('Invalid Authorization header.', null, 400);
        }
    }

    /**
     * Register api.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'password_confirmation' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        $success = [
            'token' => $user->createToken('MyApp')->accessToken,
            'email' => $user->email,
            'user' => $user,
        ];

        return $this->sendSuccess($success, 'User created.');
    }

    protected function getPermissions($simplified = true)
    {
        $permissions = \Auth::user()->getAllPermissions();
        $_permissions = [];

        if ($simplified) {
            foreach ($permissions as $permission) {
                $_permissions[] = [
                    'id' => $permission->id,
                    'name' => $permission->name,
                    'label' => $permission->label,
                ];
            }
        }

        return $simplified ? $_permissions : $permissions;
    }

    protected function getRoles()
    {
        return Auth::user()->roles()->get(['id', 'name'])->toArray();
    }
}
