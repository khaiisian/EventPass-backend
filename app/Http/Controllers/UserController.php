<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\PasswordUpdateRequest;
use App\Http\Requests\User\UserCreateRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Traits\HttpResponses;

class UserController extends Controller
{
    use HttpResponses;

    protected $_userService;

    public function __construct(UserService $userService)
    {
        $this->_userService = $userService;
    }

    public function index()
    {
        try {
            $users = UserResource::collection(
                $this->_userService->getUsers()
            );

            return $this->success(true, $users, 'Users retrieved successfully', 200);
        } catch (Exception $e) {
            Log::error('User index error: ' . $e->getMessage());
            return $this->fail(false, null, 'Failed to retrieve users', 500);
        }
    }

    public function store(UserCreateRequest $request)
    {
        try {
            $user = $this->_userService->createUser($request->validated());

            return $this->success(
                true,
                UserResource::make($user),
                'User created successfully',
                201
            );
        } catch (Exception $e) {
            Log::error('User store error: ' . $e->getMessage());
            return $this->fail(false, null, 'User creation failed', 500);
        }
    }

    public function show($id)
    {
        try {
            $user = $this->_userService->getUserById($id);

            return $this->success(
                true,
                UserResource::make($user),
                'User retrieved successfully',
                200
            );
        } catch (Exception $e) {
            return $this->fail(false, null, 'User not found', 404);
        }
    }

    public function update(UserUpdateRequest $request, $id)
    {
        try {
            $user = $this->_userService->update($request->validated(), $id);

            return $this->success(
                true,
                UserResource::make($user),
                'User updated successfully',
                200
            );
        } catch (Exception $e) {
            Log::error('User update error: ' . $e->getMessage());
            return $this->fail(false, null, 'User update failed', 500);
        }
    }

    public function updatePassword(PasswordUpdateRequest $request)
    {
        try {
            $this->_userService->updatePassword($request->validated());

            return $this->success(true, null, 'Password updated successfully', 200);
        } catch (Exception $e) {
            return $this->fail(false, null, $e->getMessage(), 400);
        }
    }

    public function destroy($id)
    {
        try {
            $this->_userService->destroy($id);
            return $this->success(true, null, 'User deleted successfully', 200);
        } catch (Exception $e) {
            return $this->fail(false, null, $e->getMessage(), 404);
        }
    }

    public function destroyMe()
    {
        try {
            $this->_userService->destroyMe();
            return $this->success(true, null, 'Account deleted successfully', 200);
        } catch (Exception $e) {
            return $this->fail(false, null, $e->getMessage(), 401);
        }
    }
}