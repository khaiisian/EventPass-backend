<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserCreateRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
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
            $lst = UserResource::collection(
                $this->_userService->getUsers()
            );

            return $this->success('success', $lst, 'Users are retrieved successfully', 200);
        } catch (Exception $e) {
            Log::error('User index error: ' . $e->getMessage());

            return $this->success('fail', null, 'Failed to retrieve users', 500);
        }
    }

    public function store(UserCreateRequest $request)
    {
        try {
            $data = $request->validated();

            $result = UserResource::make(
                $this->_userService->createUser($data)
            );

            return $this->success('success', $result, 'User account is created successfully.', 200);
        } catch (Exception $e) {
            Log::error('User store error: ' . $e->getMessage());

            return $this->fail('error', null, 'User account creation failed', 500);
        }
    }

    public function show($id)
    {
        try {
            $user = UserResource::make(
                $this->_userService->getUserByid($id)
            );

            return $this->success('success', $user, 'User is retrieved successfully', 200);
        } catch (Exception $e) {
            Log::error('User show error (ID ' . $id . '): ' . $e->getMessage());

            return $this->success('fail', null, 'User not found', 404);
        }
    }

    public function update(UserUpdateRequest $request, $id)
    {
        try {
            $data = $request->validated();

            $this->_userService->update($data, $id);

            $resUser = UserResource::make(
                $this->_userService->getUserByid($id)
            );

            return $this->success(true, $resUser, 'Successfully updated', 200);
        } catch (Exception $e) {
            Log::error('User update error (ID ' . $id . '): ' . $e->getMessage());

            return $this->fail(false, null, 'User update failed', 500);
        }
    }

    public function destroy($id)
    {
        try {
            $deleted = $this->_userService->destroy($id);

            if ($deleted) {
                return $this->success(true, null, 'Successfully deleted', 200);
            }

            return $this->fail(false, null, 'Delete failed', 500);
        } catch (Exception $e) {
            Log::error('User delete error (ID ' . $id . '): ' . $e->getMessage());

            return $this->fail(false, null, 'User delete failed', 500);
        }
    }
}