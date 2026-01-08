<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Traits\CodeGenerator;
use Exception;

class UserService
{
    use CodeGenerator;

    protected function connection(): User
    {
        return new User();
    }

    public function getUsers($perPage = 10)
    {
        return $this->connection()
            ->where('DeleteFlag', false)
            ->paginate($perPage);
    }

    public function getUserById($id)
    {
        return $this->connection()
            ->where('UserId', $id)
            ->where('DeleteFlag', false)
            ->firstOrFail();
    }

    public function createUser(array $data)
    {
        $user = auth()->user();

        $data['Password'] = Hash::make($data['Password']);

        if (!empty($data['ProfileImg'])) {
            $image = $data['ProfileImg'];
            $imageName = time() . '_' . $image->getClientOriginalName();
            $data['ProfileImg'] = $image->storeAs('images', $imageName, 'public');
        }

        $data['UserCode'] = $this->generateCode('USR', 'UserId', 'UserCode', User::class);
        $data['CreatedBy'] = $user->UserCode;
        $data['CreatedAt'] = now();
        $data['DeleteFlag'] = false;

        return $this->connection()->create($data);
    }

    public function update(array $data, $id)
    {
        $user = $this->connection()
            ->where('UserId', $id)
            ->where('DeleteFlag', false)
            ->firstOrFail();

        if (!empty($data['ProfileImg'])) {
            if ($user->ProfileImg) {
                Storage::disk('public')->delete($user->ProfileImg);
            }

            $image = $data['ProfileImg'];
            $imageName = time() . '_' . $image->getClientOriginalName();
            $data['ProfileImg'] = $image->storeAs('images', $imageName, 'public');
        }

        $data['ModifiedBy'] = auth()->user()?->UserCode ?? 'admin';
        $data['ModifiedAt'] = now();

        unset($data['Password']);

        $user->update(
            collect($data)->filter(fn($v) => $v !== null && $v !== '')->toArray()
        );

        return $user;
    }


    public function infoUpdate(array $data)
    {
        $user = auth()->user();

        if (!$user || $user->DeleteFlag) {
            throw new Exception('User Not Found.');
        }

        if (!empty($data['ProfileImg'])) {
            if ($user->ProfileImg) {
                Storage::disk('public')->delete($user->ProfileImg);
            }

            $image = $data['ProfileImg'];
            $imageName = time() . '_' . $image->getClientOriginalName();
            $data['ProfileImg'] = $image->storeAs('images', $imageName, 'public');
        }

        $data['ModifiedBy'] = $user->UserCode;
        $data['ModifiedAt'] = now();

        unset($data['Role']);

        $user->update(
            collect($data)->filter(fn($v) => $v !== null && $v !== '')->toArray()
        );

        return $user;
    }

    public function updatePassword(array $data)
    {
        $user = auth()->user();

        if (!$user || $user->DeleteFlag) {
            throw new Exception('User not found');
        }

        if (!Hash::check($data['CurrentPassword'], $user->Password)) {
            throw new Exception('Current password is incorrect');
        }

        $user->Password = Hash::make($data['Password']);
        $user->ModifiedBy = $user->UserCode;
        $user->ModifiedAt = now();

        $user->save();

        return $user;
    }

    public function destroy($id)
    {
        $user = $this->connection()
            ->where('UserId', $id)
            ->where('DeleteFlag', false)
            ->firstOrFail();

        $user->DeleteFlag = true;
        $user->ModifiedBy = $user->UserCode;
        $user->ModifiedAt = now();

        $user->save();
    }

    public function destroyMe()
    {
        $user = auth()->user();

        if (!$user || $user->DeleteFlag) {
            throw new Exception('Unauthorized');
        }

        $user->DeleteFlag = true;
        $user->ModifiedBy = $user->UserCode;
        $user->ModifiedAt = now();

        $user->save();
    }
}