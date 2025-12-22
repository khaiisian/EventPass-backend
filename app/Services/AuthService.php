<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Traits\CodeGenerator;

class AuthService
{
    use CodeGenerator;
    public function register(array $data)
    {
        // Hash and map password
        $data['Password'] = Hash::make($data['Password']);
        unset($data['Password']);
        $data['EventCode'] = $this->generateCode('USR', 'UserId', 'UserCode', User::class);
        $data['CreatedBy'] = 'admin';
        $data['CreatedAt'] = now();

        $user = User::create($data);

        $token = JWTAuth::fromUser($user);

        return ['user' => $user, 'token' => $token];
    }

    public function login(array $credentials)
    {
        // JWTAuth::attempt expects lowercase 'password'
        $token = JWTAuth::attempt([
            'email' => $credentials['Email'],
            'password' => $credentials['Password'],
        ]);

        return $token ?: false;
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return true;
    }

    public function refresh()
    {
        return JWTAuth::refresh(JWTAuth::getToken());
    }

    public function me()
    {
        return JWTAuth::user();
    }
}