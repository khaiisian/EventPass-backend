<?php
namespace App\Services;

use App\Http\Requests\UserCreateRequest;
use App\Models\User;

class UserService
{
    public function connection()
    {
        return new User;
    }

    public function getUsers()
    {
        return $this->connection()
            ->query()
            ->where('DeleteFlag', false)
            ->get();
    }

    public function getUserByid($id)
    {
        return $this->connection()
            ->where('UserId', $id)
            ->where('DeleteFlag', false)
            ->firstOrFail();
    }

    public function createUser(array $data)
    {
        return $this->connection()->query()->store($data);
    }

    public function update(array $data, $id)
    {
        $user = $this->connection()
            ->where('DeleteFlag', false)
            ->findOrFail($id);
        // if (isset($data['image'])) {
        //     $image = $data['image'];
        //     $image_name = time() . '_' . $image->getClientOriginalName();

        //     if ($battery->image) {
        //         Storage::disk('public')->delete($battery->image);
        //     }
        //     $image_path = $image->storeAs('images', $image_name, 'public');
        //     $data['image'] = $image_path;
        // }

        $user->update($data);
        return $user;
    }


    public function destroy($id)
    {
        $user = $this->connection()
            ->where('DeleteFlag', false)
            ->findOrFail($id);

        if (!$user) {
            return false;
        }

        // if ($battery->image) {
        //     Storage::disk('public')->delete($battery->image);
        // }

        $user->DeleteFlag = true;
        $user->ModifiedAt = now();
        $user->ModifiedBy = "system";

        return $user->save();
    }

    public function generateUserCode()
    {
        $lastUser = $this->connection()::orderBy('UserId', 'desc')->first();

        if (!$lastUser) {
            return 'USR0001';
        }

        $lastCode = $lastUser->UserCode;           // e.g., USR0012
        $number = (int) substr($lastCode, 3);      // 12
        $number++;
        return 'USR' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
?>