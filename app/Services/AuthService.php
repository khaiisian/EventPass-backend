<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Traits\CodeGenerator;
use Exception;

class AuthService
{
    use CodeGenerator;
    public function register(array $data)
    {
        // Hash and map password
        $data['Password'] = Hash::make($data['Password']);
        // unset($data['Password']);
        $data['UserCode'] = $this->generateCode('USR', 'UserId', 'UserCode', User::class);
        $data['CreatedBy'] = auth()->user()?->UserCode ?? 'admin';
        $data['CreatedAt'] = now();

        $user = User::create($data);

        $token = JWTAuth::fromUser($user);

        return ['user' => $user, 'token' => $token];
    }

    public function login(array $credentials)
    {
        $user = User::where('Email', $credentials['Email'])
            ->where('DeleteFlag', false) // ✅ Prevent login for deleted users
            ->first();

        if (!$user) {
            throw new Exception('Account is not found.');
        }

        // Verify password manually because JWTAuth::attempt doesn't check DeleteFlag
        if (!Hash::check($credentials['Password'], $user->Password)) {
            return false;
        }

        return JWTAuth::fromUser($user);
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
        $user = JWTAuth::user();

        if (!$user || $user->DeleteFlag) {
            return null; // or throw an exception if you prefer
        }

        return $user;
    }

}