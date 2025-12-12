<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use DateTime;
use Exception;
use Illuminate\Http\Request;
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
        //
        try {
            $lst = UserResource::collection($this->_userService->getUsers());
            return $this->success('success', $lst, 'Users are retrieved successfully', 200);
        } catch (Exception $e) {
            return $this->success('fail', null, $e->getMessage(), 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserCreateRequest $request)
    {
        try {
            $data = $request->validated();

            $data["UserCode"] = $this->_userService->generateUserCode();
            $data['CreatedBy'] = 'admin';
            $data['CreatedAt'] = now();

            $result = UserResource::make($this->_userService->createUser($data));
            return $this->success('success', $result, 'User account is created successfully.', 200);
        } catch (Exception $e) {
            return $this->fail('error', null, 'User account creation was failed', code: 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        try {
            $user = UserResource::make($this->_userService->getUserByid($id));
            return $this->success('success', $user, 'User is retrieved successfully', 200);
        } catch (Exception $e) {
            return $this->success('fail', null, $e->getMessage(), 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, $id)
    {
        //
        $validateData = $request->validated();
        $validateData['ModifiedAt'] = now();
        $validateData['ModifiedBy'] = 'admin';
        $update = $this->_userService->update($validateData, $id);
        $resUser = UserResource::make($this->_userService->getUserByid($id));
        if ($update) {
            return $this->success(true, $resUser, 'Successfully updated', 200);
        } else {
            return $this->fail(false, null, 'fail', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $battery = $this->_userService->destroy($id);
        if ($battery) {
            return $this->success(true, $battery, "Successfully deleted", 200);
        } else {
            return $this->fail(false, null, "Delete Failed", 500);
        }
    }
}