<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegistrationRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends BaseController
{
    /**
     * Login api
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        if(Auth::attempt($request->validated())){
            /** @var User $user */
            $user = Auth::user();

            $data['token'] =  $user->createToken('Auth token')->accessToken;
            $data['name'] =  $user->name;

            return $this->returnResponseSuccess($data, 'Successfully logged in!');
        }
        else{
            return $this->returnResponseError(['error' => 'Unauthorised'], __('auth.failed'));
        }
    }

    public function register(RegistrationRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        try {
            $validatedData['password'] = Hash::make($request->password);

            $user = User::create($validatedData);

            $token = $user->createToken('Auth token');

            $data = [
                'token' => $token->accessToken,
                'name' => $user->name
            ];

            return $this->returnResponseSuccess($data, 'Successfully registered!');
        } catch (\Exception $exception) {
            return $this->returnResponseError(['error' => 'Unauthorised'], 'Registration failed!');
        }
    }
}
