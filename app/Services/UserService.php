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

    protected function model()
    {
        return new User();
    }

    public function getUsers()
    {
        return $this->model()
            ->where('DeleteFlag', false)
            ->get();
    }

    public function getUserById($id)
    {
        return $this->model()
            ->where('UserId', $id)
            ->where('DeleteFlag', false) // ✅ prevent fetching deleted users
            ->firstOrFail();
    }

    public function createUser(array $data)
    {
        $data['Password'] = Hash::make($data['Password']);

        if (!empty($data['ProfileImg'])) {
            $image = $data['ProfileImg'];
            $imageName = time() . '_' . $image->getClientOriginalName();
            $data['ProfileImg'] = $image->storeAs('images', $imageName, 'public');
        }

        $data['UserCode'] = $this->generateCode('USR', 'UserId', 'UserCode', User::class);
        $data['CreatedBy'] = 'admin';
        $data['CreatedAt'] = now();
        $data['DeleteFlag'] = false;

        return $this->model()->create($data);
    }

    public function update(array $data, $id)
    {
        $user = $this->model()
            ->where('UserId', $id)
            ->where('DeleteFlag', false) // ✅ block update if soft-deleted
            ->firstOrFail();

        if (!empty($data['ProfileImg'])) {
            if ($user->ProfileImg) {
                Storage::disk('public')->delete($user->ProfileImg);
            }

            $image = $data['ProfileImg'];
            $imageName = time() . '_' . $image->getClientOriginalName();
            $data['ProfileImg'] = $image->storeAs('images', $imageName, 'public');
        }

        $data['ModifiedBy'] = 'admin';
        $data['ModifiedAt'] = now();

        $user->update(
            collect($data)->filter(fn($v) => $v !== null && $v !== '')->toArray()
        );

        return $user;
    }

    public function updatePassword(array $data)
    {
        $user = auth()->user();

        if (!$user || $user->DeleteFlag) { // ✅ block deleted users
            throw new Exception('Unauthorized');
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
        $user = $this->model()
            ->where('UserId', $id)
            ->where('DeleteFlag', false) // ✅ prevent double delete
            ->firstOrFail();

        $user->DeleteFlag = true;
        $user->ModifiedBy = 'system';
        $user->ModifiedAt = now();

        $user->save();
    }

    public function destroyMe()
    {
        $user = auth()->user();

        if (!$user || $user->DeleteFlag) { // ✅ block deleted or unauth user
            throw new Exception('Unauthorized');
        }

        $user->DeleteFlag = true;
        $user->ModifiedBy = 'system';
        $user->ModifiedAt = now();

        $user->save();
    }
}