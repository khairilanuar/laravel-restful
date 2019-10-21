<?php

namespace App\Http\Controllers\Api;

use App\Entities\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Exception;

class AuthController extends BaseController
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (! auth()->attempt($credentials)) {
            return $this->sendError('Invalid login credential.', [], Response::HTTP_UNAUTHORIZED);
        }

        $user = auth()->user();
        $user->tokens()->delete();
        $token = $user->createToken('SPA');
        $return = [
            'user'        => $user,
            'permissions' => $this->getPermissions(),
            'roles'       => $this->getRoles(),
            'token'       => $token->accessToken,
        ];

        return $this->sendSuccess($return, 'Ok.');
    }

    public function profile(Request $request)
    {
        $user = auth()->user();
        $user->permissions = $this->getPermissions();
        $user->roles = $this->getRoles();

        $return = [
            'user'        => $user,
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
        if (! config('settings.allow_public_registration')) {
            return $this->sendError('User registration is not enabled', []);
        }

        $validator = Validator::make($request->all(), $this->getValidatorRules());

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        try {
            $input = $request->all();
            $input['password'] = Hash::make($input['password']);

            if (! $user = User::create($input)) {
                return $this->sendError('Fail to create user.', $input);
            }

            // assign default user role
            $user->assignRole(config('access.users.default_role'));

            $success = [
                'token' => $user->createToken('MyApp')->accessToken,
                'email' => $user->email,
                'user'  => $user,
            ];

            return $this->sendSuccess($success, 'User created.');
        } catch (Exception $e) {
            return $this->sendError('Fail to register user.', $e->getMessage(), $e->getCode());
        }
    }

    public function forgotPassword()
    {
        // TODO: forgot password
        return $this->sendSuccess([], 'TODO: forgot password');
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

    protected function getValidatorRules()
    {
        $rules = [
            'first_name'            => 'required',
            'last_name'             => 'required',
            'email'                 => 'required|email|unique:users',
            'password'              => [
                'required',
                'string',
                'min:'.config('settings.password_strength.min_length'),
            ],
            'password_confirmation' => 'required|same:password',
        ];

        if (config('settings.password_strength.require_lowercase_character')) {
            // must contain at least one lowercase letter
            $rules['password'][] = 'regex:/[a-z]/';
        }
        if (config('settings.password_strength.require_uppercase_character')) {
            // must contain at least one uppercase letter
            $rules['password'][] = 'regex:/[A-Z]/';
        }
        if (config('settings.password_strength.require_numeric_character')) {
            // must contain at least one numeric character
            $rules['password'][] = 'regex:/[0-9]/';
        }
        if (config('settings.password_strength.require_special_character')) {
            // must contain a special character
            $rules['password'][] = 'regex:/[@$!%*#?&]/';
        }

        return $rules;
    }
}
