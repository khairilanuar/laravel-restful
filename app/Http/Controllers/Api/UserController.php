<?php

namespace App\Http\Controllers\Api;

use App\Entities\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends BaseController
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (! auth()->attempt($credentials)) {
            return $this->sendError('Unauthorized.', [], Response::HTTP_UNAUTHORIZED);
        }

        $user = auth()->user();
        $user->tokens()->delete();
        $token = $user->createToken('SPA');
        $return = [
            'user'  => $user,
            'permissions' => $this->getPermissions(),
            'roles' => $this->getRoles(),
            'token' => $token->accessToken,
        ];

        return $this->sendSuccess($return, 'Ok.');
    }

    public function profile(Request $request)
    {
        $user = auth()->user();
        $user->permissions = $this->getPermissions();
        $user->roles = $this->getRoles();

        $return = [
            'user'        => $user
        ];

        return $this->sendSuccess($return, 'Ok.');
    }

    public function logout(Request $request)
    {
        try {
            $accessTokenHeader = $request->header('Authorization');
            $accessToken = explode(' ', $accessTokenHeader)[1];
            $accessToken = explode('.', $accessToken)[0];
            $accessToken = json_decode(base64_decode($accessToken, true))->jti;

            // delete accessToken
            auth()->user()->tokens()->where('id', '=', $accessToken)->delete();

            return $this->sendSuccess([], 'Ok.');
        } catch (\Exception $e) {
            return $this->sendError('Invalid Authorization header.', null, Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Register api.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name'            => 'required',
            'last_name'             => 'required',
            'email'                 => 'required|email',
            'password'              => 'required',
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
            'user'  => $user,
        ];

        return $this->sendSuccess($success, 'User created.');
    }

    protected function getPermissions($simplified = true)
    {
        $permissions = auth()->user()->getAllPermissions();
        $_permissions = [];

        if ($simplified) {
            foreach ($permissions as $permission) {
                $_permissions[] = [
                    'id'    => $permission->id,
                    'name'  => $permission->name,
                    'label' => $permission->label,
                ];
            }
        }

        return $simplified ? $_permissions : $permissions;
    }

    protected function getRoles()
    {
        return auth()->user()->roles()->get(['id', 'name'])->toArray();
    }
}
