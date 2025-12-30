<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        try {
            $data = $request->validated();
            $result = $this->authService->register($data);

            return response()->json([
                'success' => true,
                'user' => $result['user'],
                'token' => $result['token']
            ]);
        } catch (Exception $e) {
            Log::error('Register failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->validated();
            $token = $this->authService->login($credentials);

            if (!$token) {
                Log::warning('Login failed for email: ' . $credentials['Email']);
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid email or password'
                ], 401);
            }

            return response()->json([
                'success' => true,
                'token' => $token
            ]);
        } catch (Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function logout()
    {
        try {
            $this->authService->logout();
            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully'
            ]);
        } catch (Exception $e) {
            Log::error('Logout failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function me()
    {
        try {
            $user = $this->authService->me();

            // Check if user exists and is not deleted
            if (!$user || $user->DeleteFlag) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            // If user has a profile image, prepend full URL
            if ($user->ProfileImg) {
                $user->ProfileImg = asset('storage/' . $user->ProfileImg);
            }

            return response()->json([
                'success' => true,
                'user' => $user
            ]);

        } catch (Exception $e) {
            Log::error('Fetching user info failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch user info'
            ], 500);
        }
    }

    public function refresh()
    {
        try {
            $token = $this->authService->refresh();
            return response()->json([
                'success' => true,
                'token' => $token
            ]);
        } catch (Exception $e) {
            Log::error('Token refresh failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}